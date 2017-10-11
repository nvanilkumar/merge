<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\LinksModel;
use Validator;

class LinkService
{

    protected $request;
    protected $linksModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->linksModel = new LinksModel();
        $this->linksModel->setTableName("links");
    }

    /**
     * To create the link
     * @return type
     */
    public function createLink()
    {
        //validations
        if ($this->linkValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->linkValidator())
                            ->withInput();
        }

        $insertArray = [
            "link_name" => $this->request->input("link_name"),
            "link_url" => $this->request->input("link_url"),
            "menu_position" => $this->linksModel->getMaxMenuPosition() + 1,
            "status" => "active"
        ];

        $this->linksModel->setInsertUpdateData($insertArray);
        $linkId = $this->linksModel->insertData();
        return $linkId;
    }

    /**
     * Create user related validation Rules
     * @return type
     */
    public function linkValidator()
    {
        return Validator::make($this->request->all(), [
                    'link_name' => 'required|string',
                    'link_url' => 'required',
        ]);
    }

    /**
     * To update the link details
     * @return type
     */
    public function updateLink()
    {

        //validations
        if ($this->linkValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->linkValidator())
                            ->withInput();
        }

        //update logic
        $updateArray = [
            "link_name" => $this->request->input("link_name"),
            "link_url" => $this->request->input("link_url"),
        ];
        $whereArray = [
            ["link_id", '=', $this->request->input("link_id")]
        ];
        
//        print_r($updateArray);exit;
        $this->linksModel->setInsertUpdateData($updateArray);
        $this->linksModel->setWhere($whereArray);
        $updatedId = $this->linksModel->updateData();

        return $updatedId;
    }

    /**
     * To get the specific link details 
     */
    public function getLinkDetails()
    {

        $whereArray = [
            ["link_id", '=', $this->request->input("link_id")]
        ];
        $this->linksModel->setWhere($whereArray);
        $links = $this->linksModel->getData();

        return $links;
    }

    /**
     * To get the specific link details 
     */
    public function getLinkList()
    {
        $links = $this->linksModel->getOrderByData("link_id");
        return $links;
    }

    public function updateMenuPositon()
    {
        $updateData = json_decode($this->request->input("data"));

        foreach ($updateData as $value) {

            $this->updateLinkMenuPosition($value);

        }
        return response()->json(['success' => true]);
    }

    /**
     * To update the menu order details
     * @return type
     */
    public function updateLinkMenuPosition($value)
    {


        //update logic
        $updateArray = [
            "menu_position" => $value->menu_position
        ];

        $whereArray = [
            ["link_id", '=', $value->link_id]
        ];

        $this->linksModel->setInsertUpdateData($updateArray);
        $this->linksModel->setWhere($whereArray);
        $updatedId = $this->linksModel->updateData();

        return $updatedId;
    }

    /**
     * To delete the link id
     */
    public function deleteLink()
    {
        //validations
        if ($this->deletedLinkValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->deletedLinkValidator())
                            ->withInput();
        }

        //delete logic
        $whereArray = [
            "link_id" => $this->request->input("link_id"),
        ];

        $this->linksModel->setWhere($whereArray);
        $this->linksModel->deleteData();
        return TRUE;
    }

    /**
     * delete link related validations
     * @return type
     */
    public function deletedLinkValidator()
    {
        return Validator::make($this->request->all(), [
                    'link_id' => 'required|integer'
        ]);
    }

}
