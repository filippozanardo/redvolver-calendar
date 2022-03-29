"use strict";

// Class definition

var RVKanbanBoardDemo = function () {

    return {
        // public functions
        init: function() {
          if ( $('#jkan').length > 0 ) {


      var kanmain = new jKanban({
        element          : '#jkan',                                           // selector of the kanban container
        gutter           : '5px',                                       // gutter of the board
        widthBoard       : '300px',                                      // width of the board
        responsivePercentage: false,                                    // if it is true I use percentage in the width of the boards and it is not necessary gutter and widthBoard
        dragItems        : true,                                         // if false, all items are not draggable
        boards           : boards,                                           // json of boards
        dragBoards       : false,                                         // the boards are draggable, if false only item can be dragged
        addItemButton    : false,                                        // add a button to board for easy item creation
        buttonContent    : '+',                                          // text or html content of the board button
        itemHandleOptions: {
            enabled             : false,                                 // if board item handle is enabled or not
            handleClass         : "item_handle",                         // css class for your custom item handle
            customCssHandler    : "drag_handler",                        // when customHandler is undefined, jKanban will use this property to set main handler class
            customCssIconHandler: "drag_handler_icon",                   // when customHandler is undefined, jKanban will use this property to set main icon handler class. If you want, you can use font icon libraries here
            customHandler       : "<span class='item_handle'>+</span> %s"// your entirely customized handler. Use %s to position item title
        },
        dropEl: function(el, target, source, sibling){
          // console.log(target.parentElement.getAttribute('data-id'));
          // console.log(el, target, source, sibling);

          var pid = target.parentElement.getAttribute('data-id');
          var tid = $(el).data('tid');
          console.log(pid);
          console.log(tid);

          $.ajax({
             type : "POST",
             url : rvlocalize.ajaxurl,
             data : {
                action:"rv_move_kan",
                pid: pid,
                tid: tid,
            },
            success: function(response) {
                console.log( response );
                // if(response.success) {
                //
                //   toastr.success("Successfully deleted!");
                //   setTimeout(function(){
                //     location.reload();
                //   },500);
                //
                //
                // }
              }
          });

        },
        click            : function (el) {},                             // callback when any board's item are clicked
        dragEl           : function (el, source) {},                     // callback when any board's item are dragged
        dragendEl        : function (el) {},                             // callback when any board's item stop drag
        dragBoard        : function (el, source) {
          console.log(el);
        },                     // callback when any board stop drag
        dragendBoard     : function (el) {},                             // callback when any board stop drag
        buttonClick      : function(el, boardId) {
          console.log(el);
        } ,                     // callback when the board's button is clicked
      });

      $('.close-board').on('click',function(e) {
        e.preventDefault();
        e.stopPropagation();
        // console.log( $(this).data('id') );
        var bb = $(this).data('id');
        var ii = kanmain.getBoardElements(bb);
        console.log(bb);
        var r = confirm("Sicuro?");
        if (r == true) {


          $.ajax({
             type : "POST",
             url : rvlocalize.ajaxurl,
             data : {
                action:"rv_clear_board",
                pid: bb,
            },
            success: function(response) {
                // console.log( response );
                if ( response.success ) {
                  location.reload();
                }
              }
          });

        } else {
        }

      });

      $('#prpm').select2({
        placeholder: 'Please Select a PM',
        data: rvpm
      });

      $('#kanclient').select2({
        placeholder: 'Please Select a Client',
        data: rvclientdata
      });

      var projmodal = $("#projmodal");

      $(document).on('click','.edit-proj',function(e) {
        e.preventDefault();

        var prid = $(this).data('id');
        var pmid = $(this).data('pm');
        var cid = $(this).data('cid');

        projmodal.find('#prid').val(prid);
        if ( pmid != '' ) {
          projmodal.find('#prpm').val(pmid).trigger("change");
        }else{
          projmodal.find('#prpm').val('').trigger("change");
        }


        if ( cid != '' ) {
          projmodal.find('#kanclient').val(cid).trigger("change");
        }else{
          projmodal.find('#kanclient').val('').trigger("change");
        }


        projmodal.modal({
            backdrop: 'static'
        });
      });

      $('#pmmodsave').on('click', function(e){
        var prid = $('#prid').val();
        var prpm = $('#prpm').val();
        var kanclient = $('#kanclient').val();

        e.preventDefault();
        $.ajax({
           type : "POST",
           url : rvlocalize.ajaxurl,
           data : {
              action:"rv_kan_pm",
              prid: prid,
              pmid: prpm,
              clientid: kanclient,
          },
          success: function(response) {
            if ( response.success ) {
              var out = JSON.parse(response.data.out);



              kanmain.replaceElement('_p'+prid,out);

              setTimeout(function(){
                kanmain.findElement('_p'+prid).setAttribute("data-class", "pm"+response.data.pmid);
                projmodal.modal('hide');
              },150);
            }else{
              setTimeout(function(){
                projmodal.modal('hide');
              },150);
            }

          },
        });

      });

      $('.close-proj').on('click',function(e) {
        e.preventDefault();
        var tid = $(this).data('id');
        var r = confirm("Sicuro?");
        if (r == true) {

          $.ajax({
             type : "POST",
             url : rvlocalize.ajaxurl,
             data : {
                action:"rv_clear_proj",
                tid: tid,
            },
            success: function(response) {
                // console.log( response );
                if ( response.success ) {
                  kanmain.removeElement('_p'+tid);
                }
              }
          });

        } else {
        }
      });

      $('.check-proj').on('click',function(e) {
        e.preventDefault();
        var that = this;
        var tid = $(this).data('id');
        var r = confirm("Sicuro?");
        if (r == true) {

          $.ajax({
             type : "POST",
             url : rvlocalize.ajaxurl,
             data : {
                action:"rv_check_proj",
                tid: tid,
            },
            success: function(response) {
                console.log( response );
                if ( response.success ) {
                  if ( response.data.mark == 1 ) {
                    $(that).find('.fa').removeClass('fa-check').addClass('fa-check-circle');
                  }else{
                    $(that).find('.fa').removeClass('fa-check-circl').addClass('fa-check');
                  }
                }
              }
          });

        } else {
        }
      });

      $('#solomark').on('click',function(e) {
        e.preventDefault();
        $('.fa-check').closest('.kanban-item').hide();
        // console.log( $('.fa-square-o').closest('.kanban-item')) ;
      });

      $('#tuttiproj').on('click',function(e) {
        e.preventDefault();
        $('.fa-check').closest('.kanban-item').show();
      });

      $('#clearmarked').on('click',function(e) {
        e.preventDefault();
        $.ajax({
           type : "POST",
           url : rvlocalize.ajaxurl,
           data : {
              action:"rv_clear_marked",
          },
          success: function(response) {
              // console.log( response );
              if ( response.success ) {
                location.reload();
              }
            }
        });
      });

      $('#kan-pm-select').select2({
        placeholder: 'Select a PM',
        allowClear: true
      });

      $('#kan-pm-select').on("change", function (e) {

        var pmid = $('#kan-pm-select').select2('data')[0].id;
        if ( pmid ) {
          $(".kanban-item").hide();
          $("[data-class='pm"+pmid+"']").show();
        }else{
          $(".kanban-item").show();
        }


      });


  }
        }
    };
}();

jQuery(document).ready(function() {
    RVKanbanBoardDemo.init();
});
