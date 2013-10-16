//http://stackoverflow.com/questions/2190801/passing-parameters-to-javascript-files
var MYLIBRARY = MYLIBRARY || (function(){
    var _args = {};

    return {
        init : function(id) {
            _id = id;
        },
        scroll :  (function($){
            $(window).load(function(){
                $(_id).mCustomScrollbar({
                    scrollButtons:{
                        enable:true
                    },
                theme:"dark"
            });
            });
        })(jQuery),
    };
}());