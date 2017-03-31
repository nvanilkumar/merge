<div 
    angucomplete-alt id="subCat" 
    placeholder="Search Sub Category" pause="1" selected-object="subcategorySelected"
    remote-url="/api/subcategory/search" 
    remote-url-request-formatter="remoteRequestSubCat" 
    remote-url-data-field="response.subCategoryList" 
    title-field="name" input-class="form-control form-control-small" 
    match-class="highlight" input-changed="subCatChanged">
          </div>