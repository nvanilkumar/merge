<div class="rightArea">
    <?php
    $collaboratorAddedMessage = $this->customsession->getData('collaborateAddFlashMessage');
    $this->customsession->unSetData('collaborateAddFlashMessage');
    $collaborateEditedFlashMessage = $this->customsession->getData('collaborateEditedFlashMessage');
    $this->customsession->unSetData('collaborateEditedFlashMessage');
    if ($collaboratorAddedMessage) {
        ?>
        <div  class="db-alert db-alert-success flashHide">
            <strong><?php echo $collaboratorAddedMessage; ?></strong> 
        </div>
    <?php  }?>
    <?php if ($collaborateEditedFlashMessage) { ?>
            <div  class="db-alert db-alert-success flashHide">
                <strong>  <?php echo $collaborateEditedFlashMessage; ?></strong> 
            </div>
    <?php } ?>
        <div class="heading float-left">
            <h2>Collaborators List: <span><?php echo $eventTitle; ?></span></h2>
        </div>
        <div class="clearBoth"></div>
        <div class="float-right"> <a href="<?php echo commonHelperGetPageUrl('dashboard-add-collaborator', $eventId); ?>" class="createBtn float-left font14"><span class="icon-add pinkBg"></span>Add New Collaborator</a> </div>
        <div class="clearBoth"></div>
        <input type="hidden" name="eventId" id="eventId" value="<?php echo $eventId; ?>"/>
        <div class="tablefields">
            <table width="100%" border="0" cellspacing="0" cellpadding="0"  data-tablesaw-minimap>
                <thead>
                    <tr>
                        <th scope="col" data-tablesaw-priority="persist">Collaborator Name</th>
                        <th scope="col" data-tablesaw-priority="3">Email</th>
                        <th scope="col" data-tablesaw-priority="3">Modules</th>
                        <th scope="col" data-tablesaw-priority="3">Current Status</th>
                        <th scope="col" data-tablesaw-priority="3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($errors)) { ?> 
                        <tr> 
                            <td colspan="5">
                                 <div class="db-alert db-alert-info">                    
                                    <strong><?php echo $errors[0]; ?></strong> 
                                </div>
                                
                            </td> 
                        </tr>
                        <?php
                    } else {
                        foreach ($collaboratorList as $value) {
                            ?>
                            <tr>
                                <td><?php echo $value['name']; ?></td>
                                <td><?php echo $value['email']; ?></td>
                                <td><?php
                                    $data = '';
                                    $modules = explode(',', $value['modules']);
                                    foreach ($modules as $key => $value1) {
                                        $data.=($key + 1) . '.' . ucfirst($value1) . '<br/>';
                                        $key++;
                                    }
                                    echo $data;
                                    ?></td>
                                <td><?php
                                    $className = 'greenBtn';
                                    $buttonValue = 'Active';

                                    if ($value['status'] == 0) {
                                        $className = 'orangrBtn';
                                        $buttonValue = 'Inactive';
                                    }
                                    ?>
                                    <button <?php echo 'collaboratorId=' . $value['id']; ?> type="button" class="status btn <?php echo $className; ?>"><?php echo $buttonValue; ?></button></td>
                                <td><a href="<?php echo commonHelperGetPageUrl('dashboard-edit-collaborator', $eventId . '&' . $value['id']); ?>"><span class="icon-edit"></span></span></td>
                            </tr>
                        <?php }
                    } ?>

            </tbody>
        </table>
    </div>
</div>

