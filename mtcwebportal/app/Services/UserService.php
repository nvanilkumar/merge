<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Helpers\DateHelper;
use Validator;
use App\Helpers\CustomHelper;

class UserService
{

    protected $request;
    protected $usersModel;
    protected $auth;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->usersModel = new UsersModel();

    }

    /**
     * To create the user record
     */
    public function createUser()
    {
        if ($this->createUserValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->createUserValidator())
                            ->withInput();
        }
        //Begin the db transaction
        $this->usersModel->dbTransactionBegin();

        //insert the record
        $password = Hash::make($this->request->input("password"));
        $this->usersModel->setTableName("users");
        $insertArray = [
            "first_name" => $this->request->input("first_name"),
            "last_name" => $this->request->input("last_name"),
            "username" => $this->request->input("username"),
            "password" => $password,
            "email" => $this->request->input("email"),
            "address" => $this->request->input("address"),
            "country" => $this->request->input("country"),
            "pincode" => $this->request->input("pincode"),
            "mobileno" => $this->request->input("mobileno"),
            "updated_by" => $this->request->session()->get('user_id'),
            "created_by" => $this->request->session()->get('user_id'),
            "status" => "active"
        ];

        $this->usersModel->setInsertUpdateData($insertArray);
        $userId = $this->usersModel->insertData();

        //bring the role id
        $roleName = $this->request->input("role_type");
        $roleId = $this->usersModel->getRoleId($roleName);
        if (count($roleId) == 0) {
            $this->usersModel->dbTransactionRollback();
            return FALSE;
        }

        //inert the user role data
        $this->usersModel->setTableName("role_users");
        $roleInsertArray = [
            "role_id" => $roleId,
            "user_id" => $userId
        ];
        $this->usersModel->setInsertUpdateData($roleInsertArray);
        $roleUserId = $this->usersModel->insertData();

        $this->usersModel->dbTransactionCommit();


        return $userId;
    }

    /**
     * To validate the login related details and 
     * send the response message
     * @return type
     */
    public function loginCheck()
    {

        $responseMessage = [];
        $username = $this->request->input("username");
        $password = $this->request->input("password");

        $loginStatus = Auth::attempt([
                    'username' => $username,
                    'password' => $password,
                        ], false);

        if ($loginStatus) {

            //verify login user role
            $where = [["users.user_id", "=", Auth::user()->user_id],
                ["roles.role_name", "=", "staff"]
            ];

            $this->usersModel->setWhere($where);
            $user = $this->usersModel->getUsers();

            if (count($user) > 0) {
                Session::put('user_id', Auth::user()->user_id);
                Session::put('user_name', Auth::user()->first_name . " " . Auth::user()->last_name);
                $responseMessage["response"]['status'] = true;
                $responseMessage["response"]['message'] = "Successfully login";
                $responseMessage["response"]["user_id"] = Auth::user()->user_id;
                return $responseMessage;
            }
        }

        $responseMessage["response"]['status'] = false;
        $responseMessage["response"]['message'] = "Invalid Login details";

        return $responseMessage;
    }

    /**
     * To change the user password
     * @return type
     */
    public function changePassword()
    {
        if ($this->passwordChangeValidator()->fails()) {
            $errors["status"]=false;
            $errors["message"]=$this->passwordChangeValidator()->errors()->all();
            return $errors;
            
        }
        //$responseMessage = array();
        $userId = $this->request->session()->get('user_id');
        $where = [["users.user_id", "=", $userId]];
        $this->usersModel->setWhere($where);
        $user = $this->usersModel->getUsers();

        if ($user == null) {
            return false;
        }

        $oldPassword = $this->request->input("old_password");
        $oldHashedPassword = $user[0]->password;
        $status = Hash::check($oldPassword, $oldHashedPassword);
        if ($status) {
            //change to new password
            $password = Hash::make($this->request->input("new_password"));
            $updateArray = [
                "password" => $password
            ];
            $whereArray = [
                ["user_id", '=', $userId]
            ];
            $this->usersModel->setTableName("users");
            $this->usersModel->setInsertUpdateData($updateArray);
            $this->usersModel->setWhere($whereArray);
            $this->usersModel->updateData();
          
        }else{
             $errors["status"]=false;
            $errors["message"]=["Invalid Password"];
            return $errors;
        }
        return true;
    }
    /**
     * Password update related validation Rules
     * @return type
     */
    public function passwordChangeValidator()
    {
        //echo "%%%%"; exit;
        return Validator::make($this->request->all(), [
                    'old_password' => 'required||string',
                    'new_password' => 'required||string',
                    'confirm_password' => 'required|string',
        ]);
    }
    /**
     * To update the user password to new value
     */
    public function userPasswordUpdate()
    {
        $password = Hash::make($this->request->input("new_password"));
        $updateArray = [
            "password" => $password
        ];
        $whereArray = [
            ["user_id", '=', $this->request->input("user_id")]
        ];
        $this->usersModel->setTableName("users");
        $this->usersModel->setInsertUpdateData($updateArray);
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->updateData();
    }

    /**
     * Create user related validation Rules
     * @return type
     */
    public function createUserValidator()
    {
        return Validator::make($this->request->all(), [
                    'first_name' => 'required||string',
                    'last_name' => 'required||string',
                    'email' => 'required|email|string',
                    'password' => 'required',
                    'username' => 'required||string',
        ]);
    }

    /**
     * To get the user details depends upon the where condition
     * @return type
     */
    public function getUserDetails()
    {
        $where = [];
        $this->usersModel->setTableName("users");
        $username = $this->request->input("username");
        if ($username) {
            $where[] = ["username", "=", $username];
        }

        $userIdEqual = $this->request->input("user_id_equal");
        if ($userIdEqual) {
            $where[] = ["user_id", "=", $userIdEqual];
        }

        $userIdNotEqual = $this->request->input("user_id_not_equal");
        if ($userIdNotEqual) {
            $where[] = ["user_id", "!=", $userIdNotEqual];
        }


        $this->usersModel->setWhere($where);
        $users = $this->usersModel->getData();

        return $users;
    }

    /**
     * To Update the user related details
     */
    public function updateUser()
    {
        //validations
        if ($this->updateUserValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->updateUserValidator())
                            ->withInput();
        }
        
        //update logic
        $updateArray = [
            "first_name" => $this->request->input("first_name"),
            "last_name" => $this->request->input("last_name"),
            "username" => $this->request->input("username"),
            "email" => $this->request->input("email"),
            "address" => $this->request->input("address"),
            "country" => $this->request->input("country"),
            "pincode" => $this->request->input("pincode"),
            "mobileno" => $this->request->input("mobileno"),
            "updated_by" => $this->request->session()->get('user_id'),
        ];
        

        if(strlen($this->request->input("password")) > 0){
            $updateArray["password"]=Hash::make($this->request->input("password"));
        }
        

        $whereArray = [
            ["user_id", '=', $this->request->input("user_id")]
        ];
        $this->usersModel->setTableName("users");
        $this->usersModel->setInsertUpdateData($updateArray);
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->updateData();
    }
    
    /**
     * Create user related validation Rules
     * @return type
     */
    public function updateUserValidator()
    {
        return Validator::make($this->request->all(), [
                    'first_name' => 'required||string',
                    'last_name' => 'required||string',
                    'email' => 'required|email|string',
                    'username' => 'required||string',
        ]);
    }
    
    /**
     * To get the user list depends upon 
     * the where condition based on there role
     * @return type
     */
    public function getUserList()
    {
        $where = [];
        $this->usersModel->setTableName("users");
 
        $role_name = $this->request->input("role_name");
        if ($role_name) {
            $where[] = ["roles.role_name", "=", $role_name];
        }
        
        $user_id = $this->request->input("user_id");
        if ($user_id) {
            $where[] = ["users.user_id", "=", $user_id];
        }
        $orderBy="users.user_id";
        $this->usersModel->setWhere($where);
        $users = $this->usersModel->getUsers($orderBy);

        return $users;
    }
    
     /**
     * To delete the User record  id
     */
    public function deleteUser()
    {
        //validations
        if ($this->deleteUserValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->deleteUserValidator())
                            ->withInput();
        }
        
        //check login user id
        $login_user_id = session('user_id');
        $user_id=$this->request->input("user_id");
        if($user_id == $login_user_id){
            return false;
        }

        //delete logic
        $whereArray = [
            "user_id" => $this->request->input("user_id"),
        ];
        
        //Begin the transaction
        $this->usersModel->dbTransactionBegin();
        
        
        $this->usersModel->setTableName("users");
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->deleteData();
        
        //Remove Below table links as well
        //event_users   group_users   notification_users   role_users
        //survey_users
        
        $this->usersModel->setTableName("event_users");
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->deleteData();

        $this->usersModel->setTableName("group_users");
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->deleteData();

        $this->usersModel->setTableName("notification_users");
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->deleteData();
       
        $this->usersModel->setTableName("role_users");
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->deleteData();

        $this->usersModel->setTableName("survey_users");
        $this->usersModel->setWhere($whereArray);
        $this->usersModel->deleteData();
        
        //commit the transaction
        $this->usersModel->dbTransactionCommit();
        
        return TRUE;
    }
    
    /**
     * delete user related validations
     * @return type
     */
    public function deleteUserValidator()
    {
        return Validator::make($this->request->all(), [
                    'user_id' => 'required|integer'
        ]);
    }
    
    public function assignGroupUsers()
    {
        //validations
        if ($this->assignGroupUsersValidator()->fails()) {

             redirect()->back()
                            ->withErrors($this->assignGroupUsersValidator())
                            ->withInput();
             return false;
        }
       
        $this->usersModel->setTableName("group_users");
        //update logic
        $groupId = $this->request->input("group_id");
        $userIds = $this->request->input("user_ids");

        //Bring the group related users firs
        $groupService = new GroupService($this->request);
        $groupUsers = $groupService->getGroupUsers();

        //remove dulicate user ids from the given list
        $newUsersList=[];
        if($userIds !=NULL){
            $newUsersList = CustomHelper::removeDuplicateRecords($userIds, $groupUsers);
        }

        //insert the records
        if (count($newUsersList) > 0) {
            $insertArray = [];
            foreach ($newUsersList as $userId) {
                $insertArray[] = [
                    "group_id" => $groupId,
                    "user_id" => $userId,
                    "created_at" => DateHelper::todayDateTime(),
                    "updated_at" => DateHelper::todayDateTime(),
                ];
            }
            $this->usersModel->setInsertUpdateData($insertArray);
            $this->usersModel->bulkInsert();
        }

        return true;
    }
    
     /**
     * delete assignGroupUsers related validations
     * @return type
     */
    public function assignGroupUsersValidator()
    {
        return Validator::make($this->request->all(), [
                    'group_id' => 'required|integer'
        ]);
    }
    /**
     * To Bring the dashboard details
     */
    public function dashboardDetails()
    {
        $whereArray = [
            'eventStartDate' => DateHelper::todayDate(),
            'surveyStartDate' => DateHelper::todayDate(),
           
        ];

        $this->usersModel->setWhere($whereArray);
        $details=$this->usersModel->dashboardDetails();
        return $details;
    }   

}
