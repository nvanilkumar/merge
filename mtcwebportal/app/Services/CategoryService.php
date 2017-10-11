<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\CategoryModel;
use App\Services\TopicService;
use Validator;

class CategoryService
{

    protected $request;
    protected $categoryModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->categoryModel = new CategoryModel();
        $this->categoryModel->setTableName("categories");
    }
    
       /**
     * To create the link
     * @return type
     */
    public function createCategory()
    {
        //validations
        if ($this->categoryValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->categoryValidator())
                            ->withInput();
        }

        $insertArray = [
            "category_name" => $this->request->input("category_name"),
            "status" => "active"
        ];
        
        $this->categoryModel->setInsertDataWithDates($insertArray);
        $categoryId = $this->categoryModel->insertData();
        
        return $categoryId;
    }

    /**
     * Create user related validation Rules
     * @return type
     */
    public function categoryValidator()
    {
        return Validator::make($this->request->all(), [
                    'category_name' => 'required||string',
                   
        ]);
    }
 
    
    /**
     * To get the specific link details 
    */
    public function getCategoryDetails()
    {

        $whereArray = [
            ["category_id", '=', $this->request->input("category_id")]
        ];
        $this->categoryModel->setWhere($whereArray);
        $category = $this->categoryModel->getData();

        return $category;
    }
    
    /**
     * To update the link details
     * @return type
     */
    public function updateCategory()
    {
        //validations
        if ($this->categoryValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->categoryValidator())
                            ->withInput();
        }

        //update logic
        $updateArray = [
            "category_name" => $this->request->input("category_name"),
        ];
        $whereArray = [
            ["category_id", '=', $this->request->input("category_id")]
        ];

        $this->categoryModel->setUpdateDataWithDates($updateArray);
        $this->categoryModel->setWhere($whereArray);
        $updatedId = $this->categoryModel->updateData();

        return $updatedId;
    }
    
     /**
     * To delete the category id
     */
    public function deleteCategory()
    {
        //validations
        if ($this->deletedCategoryValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->deletedCategoryValidator())
                            ->withInput();
        }

        //delete logic
        $whereArray = [
            "category_id" => $this->request->input("category_id"),
        ];

        $this->categoryModel->setWhere($whereArray);
        $this->categoryModel->deleteData();
  
        //remove topics & comments
        $topicServeice=new TopicService($this->request);
        $topicServeice->deleteCategoryTopics();
        
        return TRUE;
    }
    
     /**
     * delete category related validations
     * @return type
     */
    public function deletedCategoryValidator()
    {
        return Validator::make($this->request->all(), [
                    'category_id' => 'required|integer'
        ]);
    }
    
    public function getCategoryList()
    {
        $categories = $this->categoryModel->getOrderByData("category_id");
        return $categories;
    }        
}
