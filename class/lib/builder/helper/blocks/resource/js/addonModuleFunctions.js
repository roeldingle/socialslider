var codeHtml;
$(document).ready(function() 
{
    $('.event_mouse_over').mouseover(function() {
        $(this).addClass('over');
    }).mouseout(function() {
        $(this).removeClass('over');
    });    
    
    $('.view_code').click(function()
    {
        var sUrl = '/_addon/Module/functions-code';
        var sModuleCode = $(this).parents().parents().attr('modulecode');
        var sModuleId = $(this).parents().parents().attr('moduleid');
        var iModuleSeq = $(this).parents().parents().attr('moduleseq');
        
        sdk_popup.load('sdk_sample_code').skin('admin').layer({
            'title' : 'Sample Code',
            'classname' : 'sdk_ly_set',
            //'url' : sUrl+'?modulecode='+sModuleCode+'&moduleid='+sModuleId+'&moduleseq='+iModuleSeq,
            'width': '363',
            'openCallback' : function() {
                $('.module_id_1').val(sModuleId);
                $("._code_area").hide();
                $("#" + sModuleId).show();
                $("._code_area").each(function() {
                    html = $(this).html();
                    html = html.split('&amp;').join('&');
                    html = html.split('&lt;').join('<');
                    html = html.split('&gt;').join('>');
                    html = html.split('&amp;').join('&');
                    html = html.split('&lt;').join('<');
                    html = html.split('&gt;').join('>');
                    $(this).empty();
                    $(this).html(html);
                });
            }
        });
        
        $('.module_id_1').change(function()
        {
        	$("._code_area").hide();
        	$('#'+$(this).val()).show();
        });
    });
   
    /**
     * 
    $('.view_applied_pages').click(function()
    {
        var sUrl = '/_addon/Finder/load'; 
        var sModuleCode = $(this).parents().parents().attr('modulecode');
        var sModuleId = $(this).parents().parents().attr('moduleid');
        var iModuleSeq = $(this).parents().parents().attr('moduleseq');
        
        popup.load('applied_pages').skin('admin').layer({
            'title' : $L.get('module', 'text_applied_pages'),
            'url' : sUrl+'?modulecode='+sModuleCode+'&moduleid='+sModuleId+'&moduleseq='+iModuleSeq,
            'width': '363',
            'openCallback' : function(){
                openPopup(sModuleCode, iModuleSeq);
            }
        });
    });
    
    function openPopup(sModuleCode, iModuleSeq)
    {
        var sUrl = '/_addon/Finder/load';
        
        $('#module_id_2').change(function(){
            popup.load('applied_pages').skin('admin').layer({
                'title' : $L.get('module', 'text_applied_pages'),
                'url' : sUrl+'?modulecode='+sModuleCode+'&moduleid='+$(this).val()+'&moduleseq='+iModuleSeq,
                'width': '363',
                'openCallback' : function(){
                    openPopup(sModuleCode, iModuleSeq);
                }
            });
        });
        
        $('#SortMenu').click(function(){
            if($(this).attr('class') == "") var sOrderQuery = "&order_index=menu&order_type=asc";
            else var sOrderQuery = "&order_index=menu&order_type=desc";
            
            popup.load('applied_pages').skin('admin').layer({
                'title' : $L.get('module', 'text_applied_pages'),
                'url' : sUrl+'&modulecode='+sModuleCode+'&moduleid='+$('#module_id_2').val()+'&moduleseq='+iModuleSeq+sOrderQuery,
                'width': '363',
                'openCallback' : function(){
                    openPopup(sModuleCode, iModuleSeq);
                }
            });
        });
        
        $('#SortPage').click(function(){
            if($(this).attr('class') == "") var sOrderQuery = "&order_index=page&order_type=asc";
            else var sOrderQuery = "&order_index=page&order_type=desc";
            
            popup.load('applied_pages').skin('admin').layer({
                'title' : $L.get('module', 'text_applied_pages'),
                'url' : sUrl+'&modulecode='+sModuleCode+'&moduleid='+$('#module_id_2').val()+'&moduleseq='+iModuleSeq+sOrderQuery,
                'width': '363',
                'openCallback' : function(){
                    openPopup(sModuleCode, iModuleSeq);
                }
            });
        });
    }
     */
});
