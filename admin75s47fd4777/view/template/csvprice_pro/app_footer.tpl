<?php if (isset($phpinfo) && $phpinfo) { ?>
    <p class="text-center" style="line-height: 20px;">
        <a href="<?php echo $phpinfo; ?>" target="_blank">PHP's configuration</a>
    </p>
<?php } ?>
<div class="text-center" style="line-height: 20px;"><?php echo $text_copy; ?></div>
<div class="f-help-block f-tooltip" id="prop_desc_win">
    <div class="c_top l"></div>
    <div class="c_top r"></div>
    <div class="close" onclick="$('#prop_desc_win').hide()"></div>
    <div class="f-help-content" id="prop_desc_win_content"></div>
    <div class="c_btm l"></div>
    <div class="c_btm r"></div>
</div>
<!-- @formatter:off -->
<script>var w=0;<?php if(isset($text_confirm_delete)) { ?>$("a.delete").on("click",function(){if(!confirm("<?php echo $text_confirm_delete; ?>")){return false}});<?php } ?>$(document).ready(function(){$(".form-group label").each(function(){var d,e,f=$(this);if(f.attr("data-prop_id")){d=f.html();if(f.find("input").length){f.before('<span data-prop_id="'+f.attr("data-prop_id")+'" class="f-icon-helper" aria-hidden="true">');f.after("</span>");e=f.next()}else{f.html('<span data-prop_id="'+f.attr("data-prop_id")+'" class="f-icon-helper" aria-hidden="true">'+d+"</span>");e=f.find(".f-icon-helper")}if(e.length){e.click(function(b){var a=$(this).offset();if($("#prop_desc_win").is(":visible")&&(a.top+a.left)==w){$("#prop_desc_win").hide()}else{$("#prop_desc_win_content").html(prop_descr[$(this).attr("data-prop_id")]);$("#prop_desc_win").css({top:a.top+20,left:a.left-10}).show();w=a.top+a.left}return false})}}})});$(document).keyup(function(b){if(b.keyCode==27&&!$("#prop_desc_win").is(":hidden")){$("#prop_desc_win").hide()}});$(window).click(function(b){if(b.target.className!=="f-help-block"){$(".f-help-block").hide()}});$("#prop_desc_win").click(function(b){b.stopPropagation()});$(function(){$(".show_scroll").click(function(j){j.preventDefault();var i=$("#oc2-dialog-scroll"),e=$("#oc2-modal-body"),h=$(this).parent().attr("id"),d=0;e.html('<div class="scrollbox" style="width: 100% !important; height: 100% !important">'+$("#"+h+" div.scrollbox").html()+"</div>");$("#"+h+' input[type="checkbox"]').each(function(){$(this).attr("id","l-scroll_"+d);d++});d=0;$('#oc2-modal-body input[type="checkbox"]').each(function(){$(this).attr("id","k-scroll_"+d);if($("#l-scroll_"+d).prop("checked")){$(this).prop("checked",true)}d++});i.on("show.bs.modal",function(a){$('#oc2-modal-body input[type="checkbox"').change(function(){$("#"+$(this).attr("id").replace("k-scroll_","l-scroll_")).prop("checked",$(this).prop("checked"))})});i.on("hidden.bs.modal",function(a){$("#"+h+' input[type="checkbox"]').each(function(){$(this).attr("id","")});e.html("")});i.modal("show");return false})});</script>
<!-- @formatter:on -->

