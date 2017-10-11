<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GroupService;
use Session;

class GroupController extends Controller
{

    public function __construct(Request $request, GroupService $groupService)
    {
        $this->request = $request;
        $this->groupService = $groupService;
    }

    public function createGroupView()
    {
        return view('groups.group', ["title" => "Create Group",
            "group" => "", "userData"  => ""  ]);
    }
    
    public function createGroup()
    {
        $this->groupService->createGroup();
        return redirect('/groups/list');
    }
    
    public function updateGroupView($group_id)
    {
        $this->request->request->add(['group_id' => $group_id]);
        $groupDetails = $this->groupService->getGroupDetails();
        if ($groupDetails == NULL) {
            return redirect('/dashboard');
        }
        $group = (array) $groupDetails[0];
 
        //Assigned users list
        $userData = $this->groupService->getGroupUsers();

        return view('groups.group', ["title" => "Edit Group",
            "group" => $group,
            "userData"  => $userData  
                ]);
    }
    
    public function updateGroup()
    {
        $this->groupService->updateGroup();
        return redirect('/groups/update/' . $this->request->input("group_id"))
                 ->with('status','Group updated successfully');
    }
    
    /**
     * To delete the specified group id
     * @return type
     */
    public function deleteGroup($group_id)
    {
        $this->request->request->add(['group_id' => $group_id]);
        $this->groupService->deleteGroup();
        Session::flash('message', 'Success! Record is deleted successfully.');
        return redirect('/groups/list');
    }
    /**
     * To Bring the group list
     * @return type
     */
    public function getGrouopList()
    {
        $lists = $this->groupService->getGroupList();
        
        return view('groups.glist', ["title" => "All Groups",
            "groupList" => $lists]);
    }
    
    /**
     * To check the duplicate group name
     */
    public function groupNameCheck()
    {
        $details = $this->groupService->getGroupList();
        if ($details === NULL) {
            return "true";
        }
        return "false";
    }        

     

}
