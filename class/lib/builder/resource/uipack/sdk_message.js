var sdk_message = {
    option : {
        'id' : 'sdk_message_box'
    },

    show : function(sMessage, sType)
    {
        $('#' + this.option['id']).addClass('sdk_displaynone');
        this._setMessage(sMessage);
        this._setType(sType);
        $('#' + this.option['id']).removeClass('sdk_displaynone');
        return this;
    },
    
    hide : function()
    {
        $('#' + this.option['id']).empty();
        $('#' + this.option['id']).addClass('sdk_displaynone');
    },
    
    _setMessage : function(sMessage)
    {
        $('#' + this.option['id']).empty();
        $('#' + this.option['id']).append('<p><span>' + sMessage + '</span></p>');
    },
    
    _setType : function(sType)
    {
        if (sType == 'warning') {
            sRemoveClass = 'sdk_msg_suc_box';
            sAddClass = 'sdk_msg_warn_box';
        } else {
            sRemoveClass = 'sdk_msg_warn_box';
            sAddClass = 'sdk_msg_suc_box';
        }
        $('#' + this.option['id']).removeClass(sRemoveClass);
        $('#' + this.option['id']).addClass(sAddClass);
    }
};