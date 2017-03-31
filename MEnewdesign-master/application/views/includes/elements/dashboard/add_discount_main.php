<div class="rightArea">
    <div class="rightSec">
        <div class="search-container">
            <input class="search searchExpand icon-search"
                   id="searchId" type="search"
                   placeholder="Search">
            <a class="search icon-search"></a> </div>
    </div>
    <div class="heading float-left">
        <h2>Add / Edit Discount Code : <span>Corporate Chanakya India Tour</span></h2>
    </div>
    <div class="clearBoth"></div>
    <!--Data Section Start-->

    <div class="editFields float-left bottomAdjust100">
        <form>
            <label>Discount Code <span class="mandatory">*</span></label>
            <input type="text" class="textfield">
            <label>Discount Name <span class="mandatory">*</span></label>
            <input type="text" class="textfield">
            <div class="valid_date">
                <ul>
                    <li>
                        <label>Valid From Date <span class="mandatory">*</span></label>
                        <input type="text" class="textfield" id="startdate" >  

                    </li>
                    <li>
                        <label>Valid From Time <span class="mandatory">*</span></label>
                        <input type="text" class="textfield" id="starttime">
                    </li>
                </ul>
            </div>
            <div class="valid_date valid_till">
                <ul>
                    <li>
                        <label>Valid till Date <span class="mandatory">*</span></label>
                        <input type="text" class="textfield" id="enddate">
                    </li>
                    <li>
                        <label>Valid till Time <span class="mandatory">*</span></label>
                        <input type="text" class="textfield" id="endtime">
                    </li>
                </ul>
            </div>
            <div class="clearBoth"></div>
            <label>Discount Value <span class="mandatory">*</span> <span class="suggestiontext-g">(Enter the discount value here. For ex.200 for 200Rs)</span> </label>
            <input type="text" class="textfield">
            <label>Discount Type <span class="mandatory">*</span></label>
            <!-- <input type="checkbox" name="sport[]" value="football">-->
            <div class="valid_date valid_bottom">
                <ul>
                    <li>
                        <input type="radio" name="browser" value="firefox">
                        Amount</li>
                    <li> 
                        <input type="radio" name="browser" value="firefox">
                        Percentage </li></ul>
            </div>
            <div class="clearBoth height10"></div>

            <label>Usage Limit <span class="mandatory">*</span> <span class="suggestiontext-g">(No of registrants who can use this code)</span> </label>
            <input type="text" class="textfield">
            <div class="clearBoth height10"></div>
            <label>Ticket Type <span class="mandatory">*</span></label>
            <div class="valid_date">
                <ul>
                    <li>
                        <input type="checkbox" name="sport[]" value="football">
                        </span> Early Bird</li>
                    <li>
                        <input type="checkbox" name="sport[]" value="football">
                        </span> VVIP </li>
                </ul>
            </div>
        </form>
        <div class="btn-holder">
            <button type="button" class="createBtn float-right">Save</button>
            <a href="discount.html"><button type="button" class="saveBtn float-right">cancel</button></a>
        </div>
    </div>

</div>