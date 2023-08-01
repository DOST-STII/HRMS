function submitFormAjax(frm)
{
        
}

function errMsg(title,subtitle,type)
  {
    Swal.fire({
                title: title,
                text: subtitle,
                type: type
              });
  }


$("#frm").submit(function(e) {
e.preventDefault();    
          var formData = new FormData(this);
          Swal.fire({
                      title: 'Are you sure?',
                      text: "You won't be able to revert this!",
                      type: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Ok'
                    }).then((result) => {
                      if (result.value) {
                              $.ajax({
                                  url: $("#frm_url_action").val(),
                                  type: 'POST',
                                  beforeSend: function (xhr) {
                                      $("#overlay").show();
                                      var token = $('meta[name="csrf_token"]').attr('content');

                                      if (token) {
                                            return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                                      }
                                  },
                                  data: formData,
                                  success:function(data){
                                    $("#overlay").hide();
                                    Swal.fire({
                                              title: 'Success!',
                                              text: "",
                                              type: 'success',
                                              confirmButtonText: 'Ok'
                                                }).then((result) => {
                                                  if (result.value) {
                                                    window.location.href= $("#frm_url_reset").val();
                                                  }
                                                })

                                  },error:function(){ 
                                      $("#overlay").hide();
                                      Swal.fire({
                                                title: 'Ooppsss!',
                                                text: "Something went wrong..Page will now reload",
                                                type: 'error',
                                                confirmButtonText: 'Ok'
                                                  }).then((result) => {
                                                    if (result.value) {
                                                      location.reload();
                                                    }
                                                  })
                                  },
                                  cache: false,
                                  contentType: false,
                                  processData: false
                              });

                              return false;
                      }
                    })

});  