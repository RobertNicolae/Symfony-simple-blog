
$(document).ready(function () {

  $('#ccategory').on('submit', function (event) {
    event.preventDefault();
    let formData = new FormData(this);
    let form = $(this);
    $.ajax({
      url: "http://localhost/public/category/create",
      method: "POST",
      data: JSON.stringify({
        name: formData.get("name")
      }),
      contentType: "application/json; charset=utf-8",
      dataType: "JSON",
      async: false,
      processData: false,
      success: function (data){
        console.log(data)

      },
      error: function(err) {
        console.log(err)
      }
    })
    this.reset();
  });
});
