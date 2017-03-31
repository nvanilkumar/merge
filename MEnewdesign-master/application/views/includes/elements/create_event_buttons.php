<div class="create_eve_btns">
    <ul>
        <li>
            <button type="submit" id="save" class="btn btn-default btn-md savebtn createeventbuttons">SAVE & EXIT</button>
        </li>
        <li>
            <button type="submit" id="preview" class="btn btn-default btn-md perviewbtn createeventbuttons">PREVIEW</button>
        </li>
        <?php if(empty($eventDetails) || $eventDetails['status'] == 0 ) { ?>
        <li>
            <button type="submit" id="golive" class="btn btn-default btn-md gobtn createeventbuttons">GO LIVE</button>
        </li>
        <?php } ?>
    </ul>
</div>