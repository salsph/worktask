<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditEmployee;
use App\Position;
use Dotenv\Validator;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

use Intervention\Image\ImageManagerStatic as ImageManager;

class EmployeeController extends Controller
{
    public function index() {
        return view('employees.list');
    }

    public function list() {
        $employees = Employee::getAll();


        return datatables()->of($employees)
            ->editColumn('employee_date', function ($emp) {
                return $emp->employee_date->format('d.m.y');
            })
            ->addColumn('action', function($row){

                $btn = '<a style="padding:5px; font-size: 20px;" href="/admin/employees/editor/' . $row->id . '"><i class="fas fa-pencil-alt"></i></a>';
                $btn = $btn.' <a class="remove" data-toggle="modal" data-target="#delete" data-emp_id="' . $row->id . '" data-emp_name="' . $row->name . '" style="padding:5px; font-size: 20px;"><i class="fas fa-trash-alt"></i></a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }


    public function editor($id = 0) {
        if ($id === 0) {
            return $this->adding();
        } else {
            return $this->editing($id);
        }
    }

    public function adding() {
        $positions = Position::getAll();
        return view('employees.editor', [
            'employee' => false,
            'positions' => $positions
        ]);
    }

    public function editing($id) {
        $employee = Employee::getOne($id);
        $positions = Position::getAll();

        return view('employees.editor', [
            'employee' => $employee,
            'positions' => $positions
        ]);
    }

    public function edit(EditEmployee $request) {
        if($request->id == 0) {
            return $this->add($request);
        } else {
            return $this->update($request);
        }
    }

    public function add($request) {
        $fields = $request->all();
        unset($fields['_token']);



        if (strlen($fields['head']) > 0) {
            $newHead = Employee::getByName($fields['head']);
            $fields['head'] = $newHead->id;

            /* Check if can set this head*/
            $canHeaded = Employee::getOne($fields['head'])->level < 5;
            $mainHead = Employee::getOne(1)->name;
            if (!$canHeaded)
                return back()->withErrors(["Can't head this employee, max subordination level = 5! You can use '{$mainHead}' for any employee."]);

            $fields['level'] = $newHead->level + 1;
        } else {
            $fields['head'] = 0;
            $fields['level'] = 1;
        }

        $fields['employee_date'] = Carbon::parse($fields['employee_date'])->format('Y-m-d');
        $fields['admin_created_id'] = Auth::user()->id;
        $fields['admin_updated_id'] = Auth::user()->id;

        if($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $imageSize = getimagesize($photo->getRealPath());

            $width = Employee::$photoWidth;
            $height = Employee::$photoHeight;

            if ($imageSize[0] < $width || $imageSize[1] < $height) {
                return back()->withErrors(["Image min width & height must be 300x300px!"]);
            }

            $pathParts = pathinfo($photo->getClientOriginalName());
            $fileName = $pathParts['filename'] . '.jpg';
            $path = public_path('images/employees/' . $fileName);
            ImageManager::make($photo->getRealPath())->fit($width, $height)->encode('jpg', 80)->save($path);

            $fields['photo'] = '/images/employees/' . $fileName;
        } else {
            $fields['photo'] = '/images/employees/default.jpg';
        }

        $employee = new Employee($fields);
        $employee->save();

        return redirect('/admin/employees');
    }


    public function update($request) {
        $fields = $request->all();
        unset($fields['_token']);

        if (strlen($fields['head']) > 0) {
            $newHead = Employee::getByName($fields['head']);
            $fields['head'] = $newHead->id;

            /* Check if can set this head*/
            $canHeaded = static::canHeaded($fields['id'], $fields['head']);
            $mainHead = Employee::getOne(1)->name;
            if (!$canHeaded)
                return back()->withErrors(["Can't head this employee, max subordination level = 5! You can use '{$mainHead}' for any employee."]);

            $fields['level'] = $newHead->level + 1;
        } else {
            $fields['head'] = 0;
            $fields['level'] = 1;
        }

        $fields['employee_date'] = Carbon::parse($fields['employee_date'])->format('Y-m-d');
        $fields['admin_updated_id'] = Auth::user()->id;


        if($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $imageSize = getimagesize($photo->getRealPath());

            $width = Employee::$photoWidth;
            $height = Employee::$photoHeight;

            if ($imageSize[0] < $width || $imageSize[1] < $height) {
                return back()->withErrors(["Image min width & height must be 300x300px!"]);
            }

            $pathParts = pathinfo($photo->getClientOriginalName());
            $fileName = $pathParts['filename'] . '.jpg';
            $path = public_path('images/employees/' . $fileName);
            ImageManager::make($photo->getRealPath())->fit($width, $height)->encode('jpg', 80)->save($path);

            $fields['photo'] = '/images/employees/' . $fileName;
        }

        $updated = Employee::edit($fields['id'], $fields);

        if ($updated && $fields['level'] > 1)
            static::setNewLevelsToChildren($fields['id'], ++$fields['level']);

        return redirect('/admin/employees');
    }

    public function remove(Request $request) {
        $employee = Employee::find($request->employee_id);
        $deleted = $employee->delete();


        if($deleted && $employee->children) {
            static::setNewHeadToChildren($employee, $employee->head);
            static::setNewLevelsToChildren($employee->head, $employee->level);
        }

        return back();
    }

    public function setNewHeadToChildren($oldEmployee, $newHead) {
        $children = $oldEmployee->children;

        foreach ($children as $child) {
            Employee::edit($child->id, ['head' => $newHead]);
        }
    }


    public static function setNewLevelsToChildren($employeeId, $level) {
        $employee = Employee::getOne($employeeId);
        $children = $employee->children;

        foreach ($children as $child) {
            Employee::edit($child->id, ['level' => $level]);
        }

        foreach ($children as $child) {
            static::setNewLevelsToChildren($child->id, $level+1);
        }
    }

    /**
     * Autocomplete function for `head` input
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request) {
        $similarName = Employee::getSimilarName($request->input('query'));
        return response()->json($similarName);
    }


    /**
     * Check if $employee can be headed by $head
     * @param $employee
     * @param $head
     * @return bool
     */
    public static function canHeaded($employee, $head) {
        $current = Employee::getOne($employee);
        $newHead = Employee::getOne($head);
        $currentMaxLevel = static::getMaxLevel($current->level);

        return $currentMaxLevel + $newHead->level + 1 - $current->level <= 5;
    }

    /**
     * Get children's max level
     * @param $id
     * @return mixed
     */
    public static function getMaxLevel($id) {
        $employee = Employee::getOne($id);
        $maxLevel = $employee->level;
        $children = $employee->children;

        foreach ($children as $child) {
            return static::getMaxLevel($child->id);
        }

        return $maxLevel;
    }


    /** Seed Logic **/
    public static function setBosses() {
        static::setWorkerLevels();
    }

    public static function setWorkerLevels($curr = 1, $prev = 0, $level = 1) {

        Employee::edit($curr, ['head' => $prev, 'level' => $level]);

        $unleveled = Employee::getUnleveled();

        if (empty($unleveled)) return;

        $lowlevel = Employee::getLowLeveled();

        static::setWorkerLevels($unleveled->id, $lowlevel->id, ++$lowlevel->level);
    }

}
