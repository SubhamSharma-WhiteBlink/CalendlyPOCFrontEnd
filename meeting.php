<?php
session_start();
if(!isset($_SESSION['email'])) {
    header('Location: calendlyIntegration.php');
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>
<body ng-app="myApp" ng-controller="formCtrl">
<div class="container">
    <div class="row">
        <div class="col-md-12 text-right m-5">
            <a href="session/logout.php" type="button" class="btn btn-warning">Logout</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{scheduleUrl}}" type="button" class="btn btn-primary btn-block text-white" target="_blank">Schedule a New Meeting</a>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <table style="width: 100%;" id="meetingsDataTable" class="w-100 table table-hover table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Meeting participants</th>
                                            <th class="text-center">Meeting date & time</th>
                                            <th class="text-center">Meeting Cancel/Reschedule</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr ng-repeat="meeting in meetings">
                                            <td class="text-center">
                                                <p ng-repeat="participant in meeting.meetingParticipants" class="ml-1 mr-1 align-items-center"  style="display:inline;">
                                                    <span class="badge badge-pill badge-{{colours[($index)%5]}}">{{participant}}</span>
                                                </p>
                                            </td>
                                            <td class="text-center">{{setTime(meeting.meetingTime)}}</td>
                                            <td class="text-center">
                                                <div ng-if="meeting.isMeetingCanceled!==true">
                                                    <a href="{{meeting.cancelUrl}}" target="_blank" type="button" class="btn mr-2 mb-2 btn-danger ">Cancel Meeting</a>
                                                    <a href="{{meeting.rescheduleUrl}}" target="_blank" type="button" class="btn mr-2 mb-2 btn-primary">Reschedule Meeting</a>
                                                </div>

                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th class="text-center">Meeting participants</th>
                                            <th class="text-center">Meeting date & time</th>
                                            <th class="text-center">Meeting Cancel/Reschedule</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.2/angular.min.js" integrity="sha512-7oYXeK0OxTFxndh0erL8FsjGvrl2VMDor6fVqzlLGfwOQQqTbYsGPv4ZZ15QHfSk80doyaM0ZJdvkyDcVO7KFA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!--DataTables-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.10.19/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js" crossorigin="anonymous"></script>
<script>
    var app = angular.module('myApp', []);
    app.controller('formCtrl', function($scope,$window) {
        $scope.colours = ["primary", "secondary", "success", "warning", "danger"]
        $scope.user = "<?php Print($_SESSION['email']); ?>";
        console.log($scope.user);
        $scope.getPrevious = function (num,type){
            if(type=="HOUR"){
                if(num==0) return 23;
                return num-1
            }else if(type=="MINUTE"){
                if(num<=10) return 60-num;
                return num-10
            }
        }

        $scope.setTime= function (date) {
            const d = new Date(date);
            return d.toLocaleString();
        }

        $scope.getAllMyMeetings = function(){
            let getAllMyMeetings = {
                "url": "http://myselfhealthcalendly.herokuapp.com/calendly/getAllMeetings",
                "method": "GET",
                "timeout": 0,
                "headers": {}
            };
            $.ajax(getAllMyMeetings).done(function (response) {
                $('#meetingsDataTable').DataTable().destroy();
                $scope.meetings = response;
                $scope.$apply();
                $('#meetingsDataTable').DataTable({
                    responsive: true
                });
            });
        }
        $scope.getAllMyMeetings();

        $scope.getAllUsers = function () {
            let getAllUsers = {
                "url": "http://myselfhealthcalendly.herokuapp.com/calendly/getAllUsers",
                "method": "GET",
                "timeout": 0,
                "headers": {}
            };
            $.ajax(getAllUsers).done(function (response) {
                for (let i = 0; i < response.length; i++) {
                    if(response[i].userName == $scope.user) {
                        $scope.scheduleUrl = response[i].schedulingUrl;
                        $scope.$apply();
                    }
                }
            });
        }

        $scope.getAllUsers();
    });
</script>
</body>
</html>