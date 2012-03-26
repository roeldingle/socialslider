$(document).ready(function() {
    $('.chk_all').change(function() {
        var bChecked = $('.chk_all').prop('checked');
        if (bChecked) {
            $('.input_chk').prop('checked', true);
        } else {
            $('.input_chk').prop('checked', false);
        }
    });

    $('#seq_btn_delete').click(function() {
        if (sequenceManage.CheckboxCount()) {
            sdk_popup.load('layer_02').skin('admin').layer({
                'title' : $L.get("title", "delete") + sequenceManage.moduleName,
                'width': '252'
            });
            sequenceManage.actionButton();
        } else {
            sdk_message.show('Please make a selection from the list.', 'warning');
        }
        return false;
    });
    
    $('#sdk_seq_btn_add').click(function() {
        return sequenceManage.mostAction();
    });
    
    $('.event_mouse_over').mouseover(function() {
        $(this).addClass('over');
    }).mouseout(function() {
        $(this).removeClass('over');
    });
});

var sequenceManage = {
    /** 스크립트 실행중 여부 **/
    isRunning : false,
    /** 모듈코드 변경 **/
    setModuleInfo: function(moduleName){
        this.moduleName = moduleName;
    },
    /** 시퀀스 저장 **/
    Save : function() {
        bFlag = oValidator.button.getMessage(['module_label']);
        if (bFlag) {
            var module_label = $('#module_label').val();
            var options = {
                url : '/_sdk/api/'+usbuilder.getAppInfo('app_id')+'/builderLib',
                type : 'get',
                data : ({
                    'mode' : 'helper',
                    'helpername' : 'sequence',
                    'action' : 'add',
                    'message' : 'true',
                    'label' : module_label
                }),
                success : function () {
                    location.reload();
                }
            };
            $.Progress.show();
            $.ajax(options);
        } else {
            sequenceManage.isRunning = false;
        }
        return false;
    },
    /** 시퀀스 일괄삭제 **/
    Delete : function() {
        
        var total_checked = $("input[name='aListCheck[]']:checked").length;
        if (total_checked > 0) {
            var aSeq = [];
            $("input[name='aListCheck[]']:checked").each(function() {
                aSeq.push($(this).val());
            });
            
            var options = {
                url : '/_sdk/api/'+usbuilder.getAppInfo('app_id')+'/builderLib',
                type : 'get',
                data : 'mode=helper&helpername=sequence&action=delete&message=true&seq='+aSeq,
                success : function () {
                    location.reload();
                }
            };
            $.Progress.show();
            $.ajax(options);
        } else {
            oValidator.setDefaultMessageType('message');
            oValidator.generalPurpose.getMessage(false, $L.get("failure", "delete"));
            sequenceManage.isRunning = false;
        }
        return false;
    },
    /** 일괄삭제시, 체크된 게시물 있는지 확인 **/
    CheckboxCount : function() {
            var iCount = $("input[name='aListCheck[]']:checkbox:checked").length;
            if(iCount == 0) {
                return false;
            } else {
                return iCount;
            }
    },
    /** 시퀀스 추가 레이어팝업 호출 **/
    mostAction : function() {
        sdk_popup.load('layer_01').skin('admin').layer({
            'title' : $L.get("title", "add") + this.moduleName,
            'width': '252',
            'closeCallback' : function(){
                $('#module_label').attr('value', '');
            }
        });
        BfwValidator.removeErrorElementAction('module_label');
        sequenceManage.actionButton();
        return false;
    }
    ,/** DB에 접근하는 동작 버튼 **/
    actionButton : function() {
        $('.sdk_btn_ly').click(function() {
            if (!sequenceManage.isRunning) {
                sequenceManage.isRunning = true;
                var sElementId = $(this).attr('id');
                switch (sElementId) {
                case "buttonSequenceSave":
                    return sequenceManage.Save();
                    break;
                case "buttonSequenceDelete":
                    return sequenceManage.Delete();
                    break;
                }
            }
        });        
        return false;
    }
};