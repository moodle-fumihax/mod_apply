<?php

// needs $apply, $submit, $items, $name_pattern, $user

require_once('jbxl/jbxl_moodle_tools.php');
?>

<style type='text/css'>
  <?php
    include(__DIR__.'/styles.css');
  ?>
</style>

<?php
if ($submit) {
    //
    echo '<form action="operate_submit.php" method="post">';
    //echo '<fieldset>';
    echo '<input type="hidden" name="sesskey" value="'.sesskey().'" />';
    echo '<input type="hidden" name="operate" value="operate" />';

    //
    $align   = right_to_left() ? 'right' : 'left';
    $student = $DB->get_record('user', array('id'=>$submit->user_id));

    if ($apply->date_format=='') $apply->date_format = get_string('date_format_default', 'apply');
    $user_name = jbxl_get_user_name($student, $name_pattern);
    $title = $user_name.' ('.userdate($submit->time_modified, $apply->date_format).')';
    if ($submit_ver==0) $title .= ' '.get_string('title_draft','apply');


    echo '<div align="center">';
    echo $OUTPUT->heading($title, 3);
    echo '</div>';
    //echo '<br />';

    //
    if ($err_message!='') {
        echo $OUTPUT->box_start('mform error boxaligncenter boxwidthwide');
        echo $err_message;
        echo $OUTPUT->box_end();
    }

    //
    echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');

    foreach ($items as $item) {
        //get the values
        $params = array('submit_id'=>$submit->id, 'item_id'=>$item->id, 'version'=>$submit_ver);
        $value  = $DB->get_record('apply_value', $params);

        echo $OUTPUT->box_start('apply_print_item');
        if ($item->typ!='pagebreak' and $item->label!=APPLY_SUBMIT_ONLY_TAG
                                    and $item->label!=APPLY_ADMIN_REPLY_TAG and $item->label!=APPLY_ADMIN_ONLY_TAG and $item->typ!='fixedtitle') {
            apply_print_line_space();
            if (isset($value->value)) {
                apply_print_item_show_value($item, $value->value, $value->id);
            }
            else {
                apply_print_item_show_value($item, false, false);
            }
        }
        else if ($item->label==APPLY_ADMIN_REPLY_TAG or $item->label==APPLY_ADMIN_ONLY_TAG and $item->typ!='fixedtitle') {
            apply_print_line_space();
            if (isset($value->value)) {
                apply_print_item_submit($item, $value->value, $value->id);
            }
            else {
                apply_print_item_submit($item, false, false);
            }
        }
        echo $OUTPUT->box_end();
    }
    require('entry_info.php');

    echo $OUTPUT->box_end();

    //
    echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
    echo '<table border="0" class="operation_submit">';

    if ($apply->email_notification_user) {
        $email_str = get_string('email_entry', 'apply');
        echo '<tr>';
        echo '<td><input type="checkbox" name="send_email" value="1" /><div style="font-weight:bold;"> '.$email_str.'</div></td>';
        echo '<td>&nbsp;&nbsp;&nbsp;</td>';
        echo '<td>&nbsp;&nbsp;&nbsp;</td>';
        echo '</tr>';
    }

    $accept = '';
    $reject = '';
    if      ($submit->acked==APPLY_ACKED_ACCEPT) $accept = 'checked';
    else if ($submit->acked==APPLY_ACKED_REJECT) $reject = 'checked';
    $accept_str = get_string('accept_entry', 'apply');
    $reject_str = get_string('reject_entry', 'apply');
    //
    echo '<tr>';
    echo '<td><input type="radio" name="radiobtn_accept" value="accept" '.$accept.'/><div style="font-weight:bold;"> '.$accept_str.'</div></td>';
    echo '<td>&nbsp;&nbsp;&nbsp;</td>';
    echo '<td><input type="radio" name="radiobtn_accept" value="reject" '.$reject.'/><div style="font-weight:bold;"> '.$reject_str.'</div></td>';
    echo '</tr>';

    if (!$apply->only_acked_accept) {
        if ($submit->execd==APPLY_EXECD_DONE) $checked = 'checked';
        else                                  $checked = '';
        $execd_str = get_string('execd_entry', 'apply');

        echo '<tr>';
        echo '<td><input type="checkbox" name="checkbox_execd" value="execd" '.$checked.'/><div style="font-weight:bold;"> '.$execd_str.'</div></td>';
        echo '<td>&nbsp;&nbsp;&nbsp;</td>';
        echo '<td>&nbsp;&nbsp;&nbsp;</td>';
        echo '</tr>';
    }

    echo '</table>';
    echo $OUTPUT->box_end();

    //
    $submit_value  = 'value="'.get_string('operate_submit_button', 'apply').'"';
    $back_value    = 'value="'.get_string('back_button', 'apply').'"';
    $reset_value   = 'value="'.get_string('clear').'"';
    $submit_button = '<input name="operate_values"  type="submit" '.$submit_value.' />';
    $back_button   = '<input name="back_to_entries" type="submit" '.$back_value.' />';
    $reset_button  = '<input type="reset" '.$reset_value.' />';
    //
    echo '<input type="hidden" name="id" value="'.$cm->id.'" />';
    echo '<input type="hidden" name="submit_id"  value="'.$submit->id.'" />';
    echo '<input type="hidden" name="submit_ver" value="'.$submit->version.'" />';
    echo '<input type="hidden" name="show_all" value="'.$show_all.'" />';

    //
    echo '<br />';
    echo '<div align="center">';
    echo '<table border="0">';
    echo '<tr>';
    echo '<td>'.$submit_button.'</td>';
    echo '<td>&nbsp;&nbsp;&nbsp;</td>';
    echo '<td>'.$reset_button.'</td>';
    echo '<td>&nbsp;&nbsp;&nbsp;</td>';
    echo '<td>'.$back_button.'</td>';
    echo '</tr>';
    echo '</table>';
    echo '</div>';

    //echo '</fieldset>';
    echo '</form>';
}

//
else {
    $back_button = $OUTPUT->single_button($back_url, get_string('back_button', 'apply'));
    //
    echo '<div align="center">';
    echo $OUTPUT->heading(get_string('no_submit_data', 'apply'), 3);
    echo $back_button;
    echo '</div>';
}

