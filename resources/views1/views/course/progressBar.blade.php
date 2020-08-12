<!-- fileupload.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Ramaqat File upload with progressBar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <style>
        .progress { position:relative; width:100%; }
        .bar { background-color: #008000; width:0%; height:20px; }
         .percent { position:absolute; display:inline-block; left:50%; color: #7F98B2;}
   </style>
</head>
<body>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>



 
<div class="container">
        <h2>Ramaqat File upload with progressBar</</h2>
            <form method="POST" action="{{ URL::to('progressStore') }}" enctype="multipart/form-data">
                @csrf


                      <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" name="name[]" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
    
  </div>

                <div class="form-group">
 
                    <input name="file[]" type="file" class="form-control" ><br/>

                   <div class="alert-danger">{{$errors->first('file')}}</div>

                    <div class="progress">
                        <div class="bar"></div >
                        <div class="percent">0%</div >
                    </div>
                    
                    <br>
                    
                </div>

                <div class="form-group">
    <label for="exampleFormControlTextarea1">Description</label>
    <textarea class="form-control" name="description[]" id="exampleFormControlTextarea1" rows="3"></textarea>
  </div>


                      <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" name="name[]" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
    
  </div>
                                <div class="form-group">
 
                    <input name="file[]" type="file" class="form-control" ><br/>

                   <div class="alert-danger">{{$errors->first('file')}}</div>

                    <div class="progress">
                        <div class="bar"></div >
                        <div class="percent">0%</div >
                    </div>
                    <br>
                    
                </div>


                                <div class="form-group">
    <label for="exampleFormControlTextarea1">Description</label>
    <textarea class="form-control" name="description[]" id="exampleFormControlTextarea1" rows="3"></textarea>
  </div>



                      <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" name="name[]" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
    
  </div>
                                <div class="form-group">         
                    <input name="file[]" type="file" class="form-control" ><br/>

                   <div class="alert-danger">{{$errors->first('file')}}</div>

                    <div class="progress">
                        <div class="bar"></div >
                        <div class="percent">0%</div >
                    </div>
                    <br>
                   
                </div>

                                <div class="form-group">
    <label for="exampleFormControlTextarea1">Description</label>
    <textarea class="form-control" name="description[]" id="exampleFormControlTextarea1" rows="3"></textarea>
  </div>

 <input type="submit"  value="Submit" class="btn btn-primary">
            </form>    
</div>



<script type="text/javascript">
    $(function() {
         $(document).ready(function()
         {
            var bar = $('.bar');
            var percent = $('.percent');

      $('form').ajaxForm({
        beforeSend: function() {
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        complete: function(xhr) {
            // alert('File Uploaded Successfully');
            // window.location.href = "/fileupload";
        }
      });
   }); 
 });
</script>


</body>
</html>