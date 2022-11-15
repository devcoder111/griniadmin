function submitForm(){
    var formData = $("#addForm").serialize();
   
    $.ajax({
        type: "POST",
        url: "add-crud-data",
        data: formData,
        success: function(data) {
            if ( data.status == 200 ) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            } 
        }
    });
}
function editSubmitForm(){
    var formData = $("#updateForm").serialize();
    console.log("formData",formData);
    $.ajax({
        type: "POST",
        url : "update-crud-data",
        data: formData,
        success: function(data) {
            if ( data.status == 200 ) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            } 
        }
    });
}

$("body").on('click','.edit',function(){
        var Id = $(this).attr("data-id");
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: 'get-crud-record',
            type: 'POST',
            data: {
                    _token: CSRF_TOKEN, 
                    id:Id,
                    },
            dataType: 'text',
            success: function (data) { 
                $('.edit-crud-modal-body').html(data);
                $('#editFormModal').modal('show');
                console.log(data);
            }  
        });
    });


    $("body").on('click','.delete',function(){
        var Id = $(this).attr("data-id");
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var is_deleted = 1;

        if (confirm("Are you sure?")) {
            $.ajax({
            url: 'delete-crud',
            type: 'POST',
            data: {
                    _token: CSRF_TOKEN, 
                    id:Id,
                    is_deleted:is_deleted,
                    },
            dataType: 'JSON',
            success: function (data) { 
                if(data)
                  {
                    location.reload();
                  }
            }  
        });
        }
        
    });


$(document).ready(function() {
    getList();

    $(document).on('click', ".pagination a", function(e) {
            e.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            getList(page);
    });


    $(document).on('click', "#search", function(e) {
        e.preventDefault();
        //var page = $(this).attr('href').split('page=')[1];
        getList();
    });

//Ji

    $("body").on('click','.column_sort',function(){
         var column=$(this).attr("column-name");
         var column_order=$(this).attr("column-order");
         var payment_status = document.getElementById("payment_status").value;
         var order_status = document.getElementById("order_status").value;
         var keyword = encodeURIComponent(document.getElementById("keyword").value);
         var ship_type = document.getElementById("ship_type").value;
         var order_date_filter=document.getElementById("order_date_filter").value;
         $('#loader').removeClass('hidden');
         $.ajax({
            type: "GET",
            url : "get-crud-list?column="+ column+"&column_order="+column_order+"&payment_status="+payment_status+"&order_status="+order_status+"&keyword="+keyword+"&ship_type="+ship_type+"&order_date_filter="+order_date_filter,
            success: function(data) {
                 $('#loader').addClass('hidden');
                $("#active_sort_column").val(column);
                $("#active_sort_by").val(column_order);
               
                $('.plan-list').html(data);
                
                
            }
        });

    });
 

    function getList(page=1){
        // var sku_v = document.getElementById("sku").value; //alert(sku_v);
        var payment_status = '';
        var order_status = '';
        var keyword = '';
        var ship_type = '';
        var column= '';
        var column_order= '';
         var order_date_filter='';
         var ajaxTime= new Date().getTime();
          $('#loader').removeClass('hidden');
        $.ajax({
            type: "GET",
            url : "get-plan-list?page="+ page+"&payment_status="+payment_status+"&order_status="+order_status+"&keyword="+keyword+"&column="+ column+"&column_order="+column_order+"&ship_type="+ship_type+"&order_date_filter="+order_date_filter,
             
            success: function(data) {
               $('#loader').addClass('hidden');
               var totalTime = new Date().getTime()-ajaxTime;
               console.log("time ",totalTime);
                $('.plan-list').html(data);
               var win = $(window);
                if(win.scrollTop() == 0){
                     window.scrollTo(0, 20);
                    
                      $("#fixed-scrollbar").css("display","list-item");
                }

                     
                
            },
          
        });
    }


  $("body").on('click','.edit_order',function(){
         var Id = $(this).attr("data-id");
         var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
         var pageno=$('.pagination li.active span').text();
         $('.edit_order_button').attr('page-number',pageno);
         var field_name = $(this).attr("data-field");
        
         $.ajax({
            url: 'get-order-record-by-id',
            type: 'POST',
             data: {
                     _token: CSRF_TOKEN, 
                    id:Id,
                    },
            dataType: 'text',
             success: function (data) { 
                $('.edit-order-modal-body').html(data);
                $('#editorderModal').modal('show');
                $('#'+field_name).css('display','block');
                if(field_name == "note_field"){
                    $('#order_status_field').empty();
                    $('#color_field').css('display','block');
                }
                if(field_name == "order_status_field"){
                    $('#note_field').empty();
                    $('#color_field').css('display','none');
                }
                
           }  
         });
    });


    $("body").on('click','.edit_order_button',function(e){
         e.preventDefault();
         var pageno=$(this).attr("page-number");
         $(".edit_order_button").attr('disabled',true);
         var formData = $("#edit_order_form").serialize();
         $.ajax({
            url: 'edit-order-action',
            type: 'POST',
             data: formData,
            dataType: 'text',
            beforeSend: function() {
             $('#loader2').removeClass('hidden')
             },
             success: function (data) { 
                $('#editorderModal').modal('hide');
                
                $("#message").empty();
                $(".edit_order_button").attr('disabled',false);
                if(data == 1){
                      getList(pageno);
                    $("#message").html('<div class="alert alert-success">Data Saved Succesfully ! </div>');
                      
                      
                }else{
                    $("#message").html('<div class="alert alert-danger">Data not Saved  ! </div>');
                }
               
           },
           complete: function(){
              
             $('#loader2').addClass('hidden');
           }

         });
        
    });

    
    function getSelectedRows() {
            var selectedRows = []
            $('.order_check').each(function () {
                if ($(this).is(":checked")) {
                    var id=$(this).attr("data-id");
                    selectedRows.push(id);
                }
            });
            return selectedRows;
    }

    $("body").on('click','#fetch_order_button',function(){

        window.open('/fetch-insert-new-order', '_blank');
           
    });
    $("body").on('click','#fetch_product_button',function(){

        window.open('/fetch-insert-new-product', '_blank');
           
    });


    $("body").on('click','.row_hightlight',function(){
         var Id = $(this).attr("data-id");
         var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
         var pageno=$('.pagination li.active span').text();
         $('.edit_order_highlight').attr('page-number',pageno);
         $.ajax({
            url: 'order-highlight-popup',
            type: 'POST',
             data: {
                     _token: CSRF_TOKEN, 
                    id:Id,
                    },
            dataType: 'text',
             success: function (data) { 

                 $('.order-highlight-body').html(data);
                $('#highlightorderModal').modal('show');
                
                
           }  
         });
    });

   $("body").on('click','.edit_order_highlight',function(e){
         e.preventDefault();
         var pageno=$(this).attr("page-number");
         $(".edit_order_highlight").attr('disabled',true);
         var formData = $("#order_highlight_form").serialize();
         $.ajax({
            url: 'order-highlight-action',
            type: 'POST',
             data: formData,
            dataType: 'text',
            beforeSend: function() {
             $('#loader2').removeClass('hidden')
             },
             success: function (data) { 
                $('#highlightorderModal').modal('hide');
                
                $("#message").empty();
                $(".edit_order_highlight").attr('disabled',false);
                if(data == 1){
                      getList(pageno);
                    $("#message").html('<div class="alert alert-success">Data Saved Succesfully ! </div>');
                      
                      
                }else{
                    $("#message").html('<div class="alert alert-danger">Data not Saved  ! </div>');
                }
               
           },
           complete: function(){
              
             $('#loader2').addClass('hidden');
           }

         });
        
    });
   


    
});

