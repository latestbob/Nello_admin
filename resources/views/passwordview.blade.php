<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <!-- App css -->
        <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
</head>
<body>



    <div class="col-md-6 m-auto py-4 rounded card">


        
        <div class="text-center m-auto">

 
                <img class="text-center" src="https://admin.asknello.com/images/logo.png" alt=""style="width:100px;">

        
      
            
        
    
    </div>



        <h5 id="congrates" style="display:none;" class="alert alert-success font-weight-bold py-2">Congratulations, you have successfully registered, kindly close the tab to continue with the Chatbot.</h5>
    <div id="showme">
        <h5 class="alert alert-info font-weight-bold py-2">Hi firstname , Kindly input your password</h5>

        <form id="">
           

            <!-- <input type="hidden"id="token" class="token" name="_token" value="{{ csrf_token() }}" /> -->

            <input id="password" type="password"name="password"class="form-control"placeholder="Enter your password"required/> 

            <p  id="error"class="text-danger"style="font-size:10px;display:none;">Password must be up to 8 , also contain Capital letter, small letter, numbers and special character</p>
            <p  id="success"class="text-success"style="font-size:10px;display:none;">You have entered a valid password</p>
            <br>

            <input id="confirm" type="password"name="password_confirmation"class="form-control"placeholder="Confirm Password"required/>
            <p  id="errorconfirm"class="text-danger"style="font-size:10px;display:none;">Password not matched</p>
            <p  id="successconfirm"class="text-success"style="font-size:10px;display:none;">Password matched</p>

            <input id="firstname" type="hidden"name="firstname"value="{{$firstname}}"required>
            <input id="lastname" type="hidden"name="lastname"value="{{$lastname}}"required>
            <input id="email" type="hidden"name="email"value="{{$email}}"required>
            <input id="phone" type="hidden"name="phone"value="{{$phone}}"required>
            <input id="gender" type="hidden"name="gender"value="{{$gender}}"required>
            <input id="dob" type="hidden"name="dob"value="{{$dob}}"required>

            <input id="platform" type="hidden"name="platform"value="{{$platform}}"required>
            <input id="agent"style="visibility:hidden" type="number"name="agent_id"value="{{$agent_id}}"required>
            <input id="user_code" type="hidden"name="user_code"value="{{$user_code}}"required>
            <input id="action" type="hidden"name="action"value="{{$action}}"required>
            <br>
            <br>

            <button  type=""id="submit"class="submit btn btn-success py-2 text-light"style="display:none;">Submit Form</button>

        </form>
        </div>
        


    </div>




<!-- bundle -->
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('js/app.min.js') }}"></script>

<script>
    var showerror = false;
    

    var password = document.getElementById('password').value;
    var confirm =  document.getElementById('confirm').value;

    ///data

    

    document.getElementById('password').addEventListener('keyup',function(e){
        var passwordData = e.target.value;

    let lowerCaseLetters = /[a-z]/g;
    var upperCaseLetters = /[A-Z]/g;
    var numbers = /[0-9]/g;
    
    var special = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/g;

    if (
      !passwordData.match(lowerCaseLetters) ||
      !passwordData.match(upperCaseLetters) ||
      !passwordData.match(numbers) ||
      !passwordData.match(special) ||
      passwordData.length < 7 
    ) {
      // return NotificationManager.error(
      //   "Invalid password, password must contain,uppercase,lowercase and greater than 8 caracters"
      // );

      showerror = true
     document.getElementById('error').style="display:none"
     document.getElementById('success').style="display:none"
      
    if(passwordData.length > 0 ){
          document.getElementById('error').style="display:block"
    }
    }

    else{
      showerror = false
      if(passwordData.length > 0 ){
          document.getElementById('error').style="display:none"
          document.getElementById('success').style="display:block"

         

          
    }
    }


    })


    //confirm password

    document.getElementById('confirm').addEventListener('keyup', function(e){
        var confirm = e.target.value;
        var password = document.getElementById('password').value;

        document.getElementById('errorconfirm').style="display:none"
     document.getElementById('successconfirm').style="display:none"
        if(confirm != password){
            if(confirm.length > 0){
                document.getElementById('errorconfirm').style="display:block"
                document.getElementById('submit').style="display:none"
            }
        }

        else{
            if(confirm.length > 0){
                document.getElementById('successconfirm').style="display:block"
                document.getElementById('errorconfirm').style="display:none"
                document.getElementById('submit').style="display:block"
            }
        }
    })
  

   
   document.getElementById('submit').addEventListener('click',function(e){
       //alert('working');

       e.preventDefault()
       //var token = document.getElementById('token').value;
       document.getElementById('submit').disabled = true;
       var firstname =  document.getElementById('firstname').value;
    var lastname =  document.getElementById('lastname').value;
    var email =  document.getElementById('email').value;
    var phone =  document.getElementById('phone').value;
    var gender =  document.getElementById('gender').value;
    var dob =  document.getElementById('dob').value;
       
    var platform =  document.getElementById('platform').value;
    var agent =  document.getElementById('agent').value;
    var user_code =  document.getElementById('user_code').value;
    var action =  document.getElementById('action').value;
    var password =  document.getElementById('password').value;

    var password_confirmation = document.getElementById('password').value;

    
    console.log(firstname);
    console.log(lastname);
    console.log(typeof agent);

       //console.log(token);

       fetch("https://admin.asknello.com/api/chatbotpass", {
    method: 'post',
    body: JSON.stringify(
        {
        "firstname":firstname,
        "lastname":lastname,
        "email":email,
        "phone":phone,
        "gender":gender,
        "dob":dob,
        "platform":platform,
       "agent": agent,
        "user_code":user_code,
        "action":action,
        "password":password,
        "password_confirmation":password_confirmation,

        //"token" : token,
    }
    ),
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        // "X-CSRF-Token": token,
    }
}).then((response) => {
    return response.json()
    console.log(response)
}).then((res) => {
    
    console.log(res)
        console.log("Post successfully created!")

        document.getElementById("showme").style="display:none"
        document.getElementById("congrates").style="display:block"
    
}).catch((error) => {
    console.log(error)
})


   })

</script>
    
</body>
</html>