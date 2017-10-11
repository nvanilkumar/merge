<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use Session;

class CategoryController extends Controller
{

    public function __construct(Request $request, CategoryService $categoryService)
    {
        $this->request = $request;
        $this->categoryService = $categoryService;
    }

    public function createCategoryView()
    {
        return view('categorys.category', ["title" => "Create Category",
            "category" => ""]);
    }
    
    public function createCategory()
    {
        $this->categoryService->createCategory();
        return redirect('/categories/list');
    }
    
    public function updateCategoryView($category_id)
    {
        $this->request->request->add(['category_id' => $category_id]);
        $categoryDetails = $this->categoryService->getCategoryDetails();
        if ($categoryDetails == NULL) {
            return redirect('/dashboard');
        }
        $category = (array) $categoryDetails[0];

        return view('categorys.category', ["title" => "Edit Category",
            "category" => $category]);
    }
    
    public function updateCategory()
    {
        $this->categoryService->updateCategory();
        return redirect('/categories/update/' . $this->request->input("category_id"))
                 ->with('status','Category updated successfully');
    }
    
    /**
     * To delete the specified category id
     * @return type
     */
    public function deleteCategory($category_id)
    {
        $this->request->request->add(['category_id' => $category_id]);
        $this->categoryService->deleteCategory();
        Session::flash('message', 'Success! Record is deleted successfully.');
        return redirect('/categories/list');
    }
    /**
     * To Bring the category list
     * @return type
     */
    public function getCategoryList()
    {
        $lists = $this->categoryService->getCategoryList();

        return view('categorys.categorylist', ["title" => "All Categories",
            "categoryList" => $lists]);
    }

     

}
