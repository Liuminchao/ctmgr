<?php
$t->echo_compound_header();
$status_list = Staff::statusText();//状态
$status_css = Staff::statusCss();
$contractor_list = Contractor::compList();//承包商列表
if (is_array($rows)) {
    $j = 1;

    $t->begin_row("onclick", "getDetail(this,'{$row['con_id']}');");
    $num = ($curpage - 1) * $this->pageSize + $j++;

    $t->echo_td($rows[0]['data']);
    $t->echo_td($rows[1]['data']);
    $t->echo_td($rows[2]['data']);
    $t->echo_td($rows[3]['data']);
    $t->echo_td($rows[4]['data']);
    $t->echo_td($rows[5]['data']);
    $t->echo_td($rows[6]['data']);
    $t->echo_td($rows[7]['data']);
    $t->end_row();

}

$t->echo_grid_floor();

//$pager = new CPagination($cnt);
//$pager->pageSize = $this->pageSize;
//$pager->itemCount = $cnt;
?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.td_userphone').each(function(){
            v = $(this).text();
            v1 = see(v);
            $(this).text(v1);
        });
        $('.td_workno').each(function(){
            b = $(this).text();
            b1 = play(b);
            $(this).text(b1);
        });
    });
</script>

