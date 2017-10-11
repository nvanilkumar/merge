<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\GroupsModel;
use App\Services\UserService;
use Validator;

class GroupService
{

    protected $request;
    protected $groupModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->groupModel = new GroupsModel();
        $this->groupModel->setTableName("groups");
    }

    /**
     * To create the link
     * @return type
     */
    public function createGroup()
    {
        //validations
        if ($this->groupValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->groupValidator())
                            ->withInput();
        }

        $insertArray = [
            "group_name" => $this->request->input("group_name"),
            "updated_by" => $this->request->session()->get('user_id'),
            "created_by" => $this->request->session()->get('user_id'),
            "status" => "active"
        ];

        $this->groupModel->setInsertDataWithDates($insertArray);
        $groupId = $this->groupModel->insertData();

        //assign all users to this group
        $usersStatus = $this->request->input("users_type");
//        print_r($usersStatus);exit;
        if ($usersStatus == "all_users") {
            $this->assignGroupUsers($groupId);
        }


        return $groupId;
    }

    /**
     * Create user related validation Rules
     * @return type
     */
    public function groupValidator()
    {
        return Validator::make($this->request->all(), [
                    'group_name' => 'required||string',
        ]);
    }

    /**
     * To Insert all users to specified group
     * @param type $groupId
     * @param type $usersList
     */
    public function assignGroupUsers($groupId, $usersList = NULL)
    {
        //Bring the all students
        $userService = new UserService($this->request);
        $this->request->request->add(['role_name' => "student"]);
        $usersList = $userService->getUserList();

        //insert all users specified group
        $groupUserData = [];
        foreach ($usersList as $user) {
            $groupUserData[] = ["group_id" => $groupId,
                "user_id" => $user->user_id
            ];
        }

        $this->groupModel->setTableName("group_users");
        $this->groupModel->setInsertUpdateData($groupUserData);
        $this->groupModel->bulkInsert($groupUserData);
        return true;
    }

    /**
     * To get the specific group details 
     */
    public function getGroupDetails()
    {

        $whereArray = [
            ["group_id", '=', $this->request->input("group_id")]
        ];
        $this->groupModel->setWhere($whereArray);
        $group = $this->groupModel->getData();

        return $group;
    }

    /**
     * To update the link details
     * @return type
     */
    public function updateGroup()
    {
        //validations
        if ($this->groupValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->groupValidator())
                            ->withInput();
        }

        //update logic
        $updateArray = [
            "group_name" => $this->request->input("group_name"),
            "updated_by" => $this->request->session()->get('user_id')
        ];
        $groupId=$this->request->input("group_id");
        $whereArray = [
            ["group_id", '=',$groupId ]
        ];

        $this->groupModel->setUpdateDataWithDates($updateArray);
        $this->groupModel->setWhere($whereArray);
        $updatedId = $this->groupModel->updateData();
 
        //delete the group user and assign new users
        $this->deleteGroupUsers($groupId);
        $userService= new UserService($this->request);
        $userService->assignGroupUsers();

        return $updatedId;
    }

    /**
     * To delete the group id
     */
    public function deleteGroup()
    {
        //validations
        if ($this->deletedGroupValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->deletedGroupValidator())
                            ->withInput();
        }

        //delete logic
        $whereArray = [
            "group_id" => $this->request->input("group_id"),
        ];

        $this->groupModel->setWhere($whereArray);
        $this->groupModel->deleteData();

        //delete the group users
        $this->deleteGroupUsers($this->request->input("group_id"));
 
        

        return TRUE;
    }

    /**
     * delete group related validations
     * @return type
     */
    public function deletedGroupValidator()
    {
        return Validator::make($this->request->all(), [
                    'group_id' => 'required|integer'
        ]);
    }

    /**
     * To Bring the group details and created user details info
     * @return type
     */
    public function getGroupList()
    {
        $whereArray = [];

        $groupName = $this->request->input("group_name");
        if ($groupName) {
            $whereArray[] = ["group_name", "=", $groupName];
        }

        $groupIdNotEqual = $this->request->input("group_id_not_equal");
        if ($groupIdNotEqual) {
            $whereArray[] = ["group_id", "!=", $groupIdNotEqual];
        }

        if (count($whereArray) > 0) {
            $this->groupModel->setWhere($whereArray);
        }


        $groups = $this->groupModel->getGroupList();
        return $groups;
    }

    /**
     * To Bring the group related users information
     * @return type
     */
    public function getGroupUsers()
    {
        $whereArray = [
            ["group_users.group_id", '=', $this->request->input("group_id")]
        ];
        $this->groupModel->setTableName("group_users");
        $this->groupModel->setWhere($whereArray);
        $groups = $this->groupModel->getGroupUsers();
        return $groups;
    }

    /**
     * To delete the Specific group related Group Users
     * @param type $groupId
     */
    public function deleteGroupUsers($groupId)
    {
        $whereArray = [
            "group_id" => $groupId,
        ];
        $this->groupModel->setTableName("group_users");
        $this->groupModel->setWhere($whereArray);
        $this->groupModel->deleteData();
    }
    
     /**
     * To Bring the selected groups related users information
     * @return type
     */
    public function getMultiGroupUsers()
    {
        $whereArray = [
            ["group_users.group_id", '=', $this->request->input("group_list")]
        ];
        $this->groupModel->setTableName("group_users");
        $this->groupModel->setWhere($whereArray);
        $groups = $this->groupModel->getGroupUsers();
        return $groups;
    }
    

}
