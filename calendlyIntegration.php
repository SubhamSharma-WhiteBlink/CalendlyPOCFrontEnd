<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <title>Tittle</title>
</head>
<body ng-app="myApp" ng-controller="formCtrl">
<div class="container">
    <div class="row m-5">
        <div class="col-md-12 text-center">
            <input type="email" class="form-control" id="exampleInputEmail1" ng-model="emailAddress" aria-describedby="emailHelp" placeholder="Enter email Address">
            <br/>
            <div class="d-inline">
                <button type="button" class="btn btn-warning m-3" id="loginButton" ng-disabled="emailAddress==undefined" ng-click="login(emailAddress)">Login</button>
                <button type="button" class="btn btn-warning m-3" id="calendlyConnectButton" ng-disabled="emailAddress==undefined" ng-click="connectCalendly(emailAddress)">Connect Calendly</button>
            </div>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="connectCalendlyToaster" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-dark text-white">
            <strong class="me-auto">Connect Request Send Successfully!</strong>
            <button type="button" class="btn-close btn" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-dark text-white">
            Check Your Mail ID for details.
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="loginSuccess" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-dark text-white">
            <strong class="me-auto">Success!</strong>
            <button type="button" class="btn-close btn" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-dark text-white">
            Login Success
        </div>
    </div>
</div>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="loginSuccessError" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-danger text-white">
            Please Connect Calendly First!
        </div>
    </div>
</div>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="error" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-danger text-white">
           Something Went Wrong!
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.2/angular.min.js" integrity="sha512-7oYXeK0OxTFxndh0erL8FsjGvrl2VMDor6fVqzlLGfwOQQqTbYsGPv4ZZ15QHfSk80doyaM0ZJdvkyDcVO7KFA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var app = angular.module('myApp', []);
    app.controller('formCtrl', function($scope,$http,$window) {
        $scope.connectCalendly=function(data) {
            $('#calendlyConnectButton').val('Connecting').attr("disabled", true);
            let loginAjaxSettings = {
                "url": "http://myselfhealthcalendly.herokuapp.com/calendly/sendInvintation"+data,
                "method": "GET",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
            };

            $.ajax(loginAjaxSettings).done(function (response) {
                $('#connectCalendlyToaster').toast("show");
                $('#calendlyConnectButton').val('Connect Calendly').attr("disabled", false);
            }).fail(function (error) {
                $('#error').toast("show");
                $('#calendlyConnectButton').val('Connect Calendly').attr("disabled", false);
                console.log(error);
            })
        }
        $scope.login=function(data) {
            $('#loginButton').val('Logging in..').attr("disabled", true);
            let loginAjaxSettings = {
                "url": "http://myselfhealthcalendly.herokuapp.com/calendly/checkStatus"+data,
                "method": "GET",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
            };

            $.ajax(loginAjaxSettings).done(function (response) {
                console.log(response);
                if(response===true) {
                    let setSessionAjaxSettings = {
                        "url": "session/setsession.php",
                        "method": "POST",
                        "timeout": 0,
                        "headers": {
                            "Content-Type": "application/json"
                        },
                        "data": data,
                    };
                    $.ajax(setSessionAjaxSettings).done(function (response) {
                        console.log(response);
                        $('#loginSuccess').toast("show");
                        $('#loginButton').val('Login').attr("disabled", false);
                        $window.location.href = "index.php";

                    }).fail(function (error) {
                        $('#error').toast("show");
                        $('#loginButton').val('Login').attr("disabled", false);
                        console.log(error);
                    })
                } else {
                    $('#loginSuccessError').toast("show");
                    $('#loginButton').val('Login').attr("disabled", false);
                }
            }).fail(function (error) {
                $('#error').toast("show");
                $('#loginButton').val('Login').attr("disabled", false);
                console.log(error);
            })
        }
    });
</script>
</body>
</html>