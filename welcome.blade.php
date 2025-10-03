<div>
<!-- <x-message-banner msg="Data successfully " class="success" />
<x-message-banner msg="Plase Secure Your Data" class="warning" />
<x-message-banner msg="Try again Plase show are Error message" class="error" /> -->
<script>
    const name = prompt("Welcome! Please enter your name:");
    console.log("Welcome Mr. " + name);
</script>

 <style>
        .container{
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .city{
            margin: 10px 0;
        }
        .city label{
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .city select{
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .input[type="checkbox"], .input[type="radio"]{
            margin-right: 10px;
        }
        .input[type="checkbox"]:hover, .input[type="radio"]:hover{
            cursor: pointer;
        }   
        .select{
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .select:hover{
            cursor: pointer;
        }   
        input[type="text"], input[type="email"], input[type="password"]{
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button{
            background: blue;
            color: white;           
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover{
            background: darkblue;
        }
        .checkbox{
            margin: 10px 0;
            display: inline-block;
            align-items: center;
            text-transform: capitalize;
            gap: 10px;
        }
        .checkbox label{
            margin-right: 10px;
        }
        .gender{
            margin: 10px 0;
            display: inline-block;
            align-items: center;
            text-transform: capitalize;
            gap: 10px;
        }
        .alertmsg{
            background: #ff5454ff;
            color: white;
            padding: 10px;
            text-align: center;     
            font-weight: 600;
            margin: 10px;
            border-radius: 10px;
            animation: fadeIn 0.5s ease-in-out;
            
        
        }
        @keyframes fadeIn{
            from{
                opacity: 0;
            }
            to{
                opacity: 1;
            }
        }
        span{
            color: red;
            font-weight: bold;
        }

    </style>
  
   <div class="container">
         <h1>User Data</h1>
         <form action="user" method="POST">
         @csrf
              <div class="mb-3">
                <span> @error('name'){{ $message }}@enderror</span>
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
              </div>
              <div class="mb-3">
                <span>@error('email'){{ $message }}@enderror</span>
                   <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
              </div>
              <div class="mb-3">
                <span>@error('password'){{ $message }}@enderror</span>
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
              </div>
            <h4> Select Your gender</h4> 
              <span> @error('gender'){{ $message }}@enderror</span>
              <br>
              <div class="gender">
                <label for="male">Male</label>
                <input type="radio" name="gender" value="male" id="male">
              </div>
              <div class="gender">
                <label for="female">Female</label>
                <input type="radio" name="gender" value="female" id="female">
              </div>
              <h4>Select Your Choice</h4>
              <span>@error('book'){{ $message }}@enderror</span><br>
              <div class="checkbox">
              <label class="checkbox" for="laptop">Laptop</label>
              <input type="checkbox" name="book[]" value="Laptop" id="laptop">
              </div>
              <div class="checkbox">
              <label  for="phone">Phone</label>
              <input type="checkbox" name="book[]" value="phone" id="phone">
               </div>
              <div class="checkbox">
                <label for="ipad">Ipad</label>
              <input type="checkbox" name="book[]" value="ipad" id="ipad">
               </div>
                <div class="city">
                    <label for="city">City</label>
                    <select name="city" id="city" class="form-select">
                        <option value="select Your City">Select Your City</option>
                        <option value="newyork">New York</option>
                        <option value="losangeles">Los Angeles</option>
                        <option value="chicago">Chicago</option>
                        <option value="houston">Houston</option>
                        <option value="phoenix">Phoenix</option>
                    </select>
                </div>
              <button type="submit" class="btn btn-primary">Submit</button>
                     <!-- this are showing error message -->
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="alertmsg">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
         </form>
   </div>
</div>
