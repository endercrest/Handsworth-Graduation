function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

var taskApp = angular.module("taskApp", ["ngCookies", "ngSanitize", "ngFileUpload"]);

//////////////////////////////////////////////////////////
/////                  Token Factory                 /////
//////////////////////////////////////////////////////////
taskApp.factory("tokenCheck", ['$cookies', "$http", "pathStarter", function ($cookies, $http, pathStarter) {
    var token = $cookies.get("TOKEN");
    var http = $http({method: "PUT", url: pathStarter+"api/token.php", params: {TOKEN: token}});
    http.success(function (response) {
        if(!response.VALID && response.TOKEN != "INVALID"){
            $http({method: "DELETE", url: pathStarter+"api/token.php", params: {TOKEN: token}})
                .success(function(response){
                    var now = new Date();
                    now.setDate(now.getDate() - 1);

                    $cookies.remove("TOKEN", {"path": "/", "expires: ": now});
                });
        }
    });
    return{
        tokenCheck: function () {
            return http
        }
    };
}]);

//////////////////////////////////////////////////////////
/////             Navigation Controllers             /////
//////////////////////////////////////////////////////////
/**
 * Navigation account drop down controller
 */
taskApp.controller("nav.accountDrop", ["$scope", "$http", "tokenCheck", "$sce", "pathStarter", function($scope, $http, tokenCheck, $sce, pathStarter){
    tokenCheck.tokenCheck().success(function (response) {
        if(response.VALID){
            $http({method: "GET", url: pathStarter+"api/user.php", params: {TOKEN: response.TOKEN, USER_ID: response.USER_ID, DATA: ["first_name", "last_name", "profile_picture"]}, paramSerializer: "$httpParamSerializerJQLike"})
                .success(function(response2){
                    $scope.info = [];
                    $scope.info.img = pathStarter+response2.profile_picture;
                    $scope.info.main = response2.first_name + " " + response2.last_name;
                    if(response.PERMISSION == 2) {
                        $scope.info.sub = "Student";
                    }else if(response.PERMISSION == 1){
                        $scope.info.sub = "Staff";
                    }else if(response.PERMISSION == 0){
                        $scope.info.sub = "System Administrator";
                    }
                    $scope.nav = [];
                    $scope.nav.img = pathStarter+response2.profile_picture;
                    $scope.nav.text = response2.first_name + " " + response2.last_name;
                    $scope.style = [];
                    $scope.style.height = "175px";
                    $scope.buttons = [];
                    $scope.buttons.left = [];
                    $scope.buttons.right = [];
                    $scope.buttons.left.title = "Logout";
                    $scope.buttons.left.url = pathStarter+"logout/";
                    $scope.buttons.right.title = "Profile";
                    $scope.buttons.right.url = pathStarter+"profile/";
                });
        }else{
            $scope.info = [];
            $scope.info.img = "";
            $scope.info.main = "You are not signed in. Please sign in or create an account if you want to submit files";
            $scope.info.sub = "";
            $scope.nav = [];
            $scope.nav.img = "";
            $scope.nav.text = $sce.trustAsHtml("Account <i class='fa fa-caret-down'></i>");
            $scope.style = [];
            $scope.style.height = "115px";
            $scope.buttons = [];
            $scope.buttons.left = [];
            $scope.buttons.right = [];
            $scope.buttons.left.title = "Login";
            $scope.buttons.left.url = pathStarter+"login/";
            $scope.buttons.right.title = "Register";
            $scope.buttons.right.url = pathStarter+"register/";
        }
    });
}]);

/**
 * Navigation pages
 *
 *
 */
taskApp.controller("nav.pages", ["$scope", "$http", "$window", "tokenCheck",  function($scope, $http, $window, tokenCheck){
    //TODO Add navigation ordering
    tokenCheck.tokenCheck().success(function(response){
        var path = $window.location.pathname;
        $scope.pages = [];
        $scope.pages.main = [
            {name: "Home", icon: "home", url: "", color: "black", active: path.substr(1, 1) == "" || path.substr(1,1) == "i"},
            {name: "Blog", icon: "bookmark", url: "blog", color: "black", active: path.substr(1, 6) == "blog/"},
            {name: "Custom Page 1", "icon": "circle", color: "blue", active: path.substr(1, 6) == "something/"}
        ];

        //FIXME Active only works when the site is not in a directory.
        if(response.VALID) {
            if(response.PERMISSION == 2) {
                $scope.pages.sub = [
                    {name: "Tasks", icon: "tasks", "url": "tasks", color: "black", active: path.substr(0, 7) == "/tasks/"}
                ];
            }else if(response.PERMISSION == 1){
                $scope.pages.sub = [
                    {name: "Manage Tasks", icon: "tasks", url: "admin/tasks", color: "black", active: path.substr(0, 13) == "/admin/tasks/"},
                    {name: "View Submissions", icon: "file", url: "admin/submissions", color: "black", active: path.substr(0, 19) == "/admin/submissions/"},
                    {name: "Manage Users", icon: "users", url: "admin/users", color: "black", active: path.substr(0, 13) == "/admin/users/"}
                ];
            }
        }
    });
}]);

//////////////////////////////////////////////////////////
/////             Credentials Controllers            /////
//////////////////////////////////////////////////////////

taskApp.controller("cred.login", ['$cookies', '$window', '$scope', '$http', 'tokenCheck', "pathStarter", function($cookies, $window, $scope, $http, tokenCheck, pathStarter){
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID){
            $window.location = "../blog/";
        }
    });

    $scope.valid = true;

    $scope.processL = function(){
        $http({method: "POST", url: pathStarter+"api/token.php", data: {student_id: $scope.user.student_id, password: $scope.user.password}})
            .then(function(response){
                $scope.valid = [];
                if(response.data.VALID){
                    $scope.valid.status = true;
                    var now = new Date();
                    now.setDate(now.getDate() + 1);

                    $cookies.put("TOKEN", response.data.TOKEN, {
                        expires: now,
                        path: "/"
                    });
                    $window.location = "../";
                }else{
                    $scope.valid.status = false;
                    console.log(response.data);
                    if(response.data.REASON == 0) {
                        $scope.valid.reason = "Credentials are incorrect!";
                    }else if(response.data.REASON == 1){
                        $scope.valid.reason = "Account has not been claimed yet!";
                    }
                }
            });
    }

}]);

taskApp.controller("cred.logout", ["$cookies", "$window", "$scope", "$http", "pathStarter", function($cookies, $window, $scope, $http, pathStarter){
    var token = $cookies.get("TOKEN");
    if(token != ""){
        $http({method: "DELETE", url: pathStarter+"api/token.php", params: {TOKEN: token}})
            .success(function(response){
                var now = new Date();
                now.setDate(now.getDate() - 1);

                $cookies.remove("TOKEN", {"path": "/", "expires: ": now});
            });
    }
    $window.location = "../";
}]);

taskApp.controller("cred.register", ["$cookies", "$window", "$scope", "$http", "pathStarter", function($cookies, $window, $scope, $http, pathStarter){
    $scope.invalid = [];
    $scope.invalid.password = false;
    $scope.invalid.student_id = false;
    $scope.error = [];
    $scope.error2 = [];
    $scope.info = [];

    //Step one
    $scope.mode = 1;

    $scope.nextStep1 = function(){
        if($scope.user.password == null || $scope.user.student_id == null){
            if($scope.user.student_id == null){
                $scope.invalid.student_id = true;
            }
            if($scope.user.password == null){
                $scope.invalid.password = true;
            }
        }else {
            $http({method: "POST", url: pathStarter+"api/claim.php", params: {REQUEST: "authentication"}, data: {student_id: $scope.user.student_id, password: $scope.user.password}})
                .success(function (response) {
                    if(response.VALID){
                        $http({method: "POST", url: pathStarter+"api/claim.php", params: {REQUEST: "name"}, data: {student_id: $scope.user.student_id, password: $scope.user.password}})
                            .success(function (response1) {
                                $scope.mode = 2;

                                $scope.info.name = response1.first_name+" "+response1.last_name;
                                $scope.error.status = false;
                            });
                    }else{
                        $scope.status = true;
                        if(response.REASON == 0){
                            $scope.error.reason = "Incorrect Credentials";
                        }else if(response.REASON == 2){
                            $scope.error.reason = "Account is already activated"
                        }else{
                            $scope.error.reason = "Something went wrong!";
                        }
                    }
                });
        }
    };
    //Step two
    $scope.nextStep2 = function(){
        console.log();
        if($scope.user_two != null) {
            if ($scope.user_two.email != null && $scope.user_two.password != null && $scope.user_two.repeat_password != null) {
                if ($scope.user_two.password == $scope.user_two.repeat_password) {
                    if ($scope.user_two.password.length > 3) {
                        $http({
                            method: "POST",
                            url: pathStarter + "api/claim.php",
                            params: {REQUEST: "update_info"},
                            data: {
                                student_id: $scope.user.student_id,
                                password: $scope.user.password,
                                email: $scope.user_two.email,
                                new_password: $scope.user_two.password
                            }
                        })
                            .success(function () {
                                $http({
                                    method: "POST",
                                    url: pathStarter + "api/claim.php",
                                    params: {REQUEST: "claim_account"},
                                    data: {student_id: $scope.user.student_id, password: $scope.user_two.password}
                                })
                                    .success(function () {
                                        $scope.mode = 3;
                                    })
                            });
                    } else {
                        $scope.error2.status = true;
                        $scope.error2.reason = "Password must be longer than 3 characters";
                    }
                } else {
                    $scope.error2.status = true;
                    $scope.error2.reason = "Passwords must repeat";
                }
            }
        }
    }
}]);

//////////////////////////////////////////////////////////
/////                 Home Controllers               /////
//////////////////////////////////////////////////////////
taskApp.controller("home", ["$scope", "tokenCheck", function ($scope, tokenCheck) {
    tokenCheck.tokenCheck().success(function(response){
        $scope.loggedIn = response.VALID;
    });
}])

//////////////////////////////////////////////////////////
/////               Tasks Controllers                /////
//////////////////////////////////////////////////////////
taskApp.controller("task", ["$scope", "$http", "tokenCheck", "$window", "pathStarter", function ($scope, $http, tokenCheck, $window, pathStarter) {
    tokenCheck.tokenCheck().then(function(response){
        if(response.data.VALID){
            $scope.tasksMode = getParameterByName("task") == "";
        }else{
            $window.location.href = pathStarter+"login/";
        }
    });
}]);

taskApp.controller("task.new", ["$scope", "$http", "tokenCheck", "$window", "pathStarter", function ($scope, $http, tokenCheck, $window, pathStarter) {
    tokenCheck.tokenCheck().success(function(response){
        $scope.createTask = function(){
            if($scope.task.name != null || $scope.task.description != null || $scope.task.due != null || $scope.task.global != null){
                $http({method: "POST", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, REQUEST: "task"}, data: {name: $scope.task.name, description: $scope.task.description, due: $scope.task.due, global: $scope.task.global}})
                    .success(function(response1){
                        $window.location.href= pathStarter+"admin/tasks/?task="+response1.task.id;
                    });
            }
        }
    });
}]);

taskApp.controller("task.admin", ["$scope", "$http", "tokenCheck", "$window", "pathStarter", function ($scope, $http, tokenCheck, $window, pathStarter) {
    tokenCheck.tokenCheck().then(function(response){
        if(response.data.VALID && response.data.PERMISSION < 2){
            $scope.tasksMode = getParameterByName("task") == "";
        }else{
            $window.location.href = pathStarter+"login/";
        }
    });
}]);

taskApp.controller("tasks.list", ["$scope", "$http", "tokenCheck", "pathStarter", function($scope, $http, tokenCheck, pathStarter){
    tokenCheck.tokenCheck().success(function(response){
        var page = (getParameterByName("page")-1)*10;
        if(getParameterByName("page") == ""){
            page = 0;
        }

        if(response.VALID){
            $http({method: "GET", url: pathStarter+"api/task.php", params: {LIMIT: 10, PAGE: page, REQUEST: "task_info", USER_ID: response.USER_ID,TOKEN: response.TOKEN, DATA: ["id", "name", "description", "due", "status"]}, paramSerializer: "$httpParamSerializerJQLike"})
                .success(function(response2){
                    $scope.tasks = response2.tasks;
                });
        }
    })
}]);

taskApp.controller("task.admin.info", ["$window", "$scope", "$http", "tokenCheck", "pathStarter", function ($window, $scope, $http, tokenCheck, pathStarter) {
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID){
            var task = getParameterByName("task");
            if(task != "") {

                $http({method: "GET", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, REQUEST: "task_info", TASK_ID: task, DATA: ["name", "due", "description", "global"]}, paramSerializer: "$httpParamSerializerJQLike"})
                    .success(function (response2) {
                        $scope.task = response2.task;
                    });

                $scope.submitUpdate = function(){
                    //TODO File Types
                    var name = angular.element("[name='taskName']").val();
                    var description = angular.element("[name='taskDescription']").val();
                    var due = angular.element("[name='taskDue']").val();
                    var global = angular.element("[name='taskGlobal']").val();
                    $http({method: "PUT", url: pathStarter+"api/task.php", params:{TOKEN: response.TOKEN, TASK_ID: task, REQUEST: "task_info"}, data: {name: name, description: description, due: due, global: global}})
                        .success(function(response1){
                            $window.location.reload();
                        }).error(function(response1){
                            console.log(response1);
                        });
                }
            }
        }
    });
}]);

taskApp.controller("tasks.admin.list", ["$scope", "$http", "tokenCheck", "pathStarter", function($scope, $http, tokenCheck, pathStarter){
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID) {
            var page = (getParameterByName("page") - 1) * 10;
            if (getParameterByName("page") == "") {
                page = 0;
            }

            if (response.VALID) {
                $http({
                    method: "GET",
                    url: pathStarter + "api/task.php",
                    params: {
                        LIMIT: 10,
                        PAGE: page,
                        REQUEST: "task_info",
                        TOKEN: response.TOKEN,
                        DATA: ["id", "name", "description", "due"]
                    },
                    paramSerializer: "$httpParamSerializerJQLike"
                })
                    .success(function (response2) {
                        $scope.tasks = response2.tasks;
                    });
            }
        }
    })
}]);

taskApp.controller("tasks.admin.pagination",["$scope", "$http", "tokenCheck", "pathStarter", function($scope, $http, tokenCheck, pathStarter){
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID) {
            var page = getParameterByName("page");
            if (getParameterByName("page") == "") {
                page = 1;
            }

            $http({
                method: "GET",
                url: pathStarter + "api/task.php",
                params: {TOKEN: response.TOKEN, REQUEST: "task_info", DATA: ["id"]},
                paramSerializer: "$httpParamSerializerJQLike"
            })
                .then(function (response) {
                    $scope.size = Math.ceil(response.data.tasks.length / 10) >= parseInt(page) + 1;
                    $scope.back = {
                        "page": parseInt(page) - 1,
                        "enabled": parseInt(page) - 1 > 0
                    };
                    $scope.next = {
                        "page": parseInt(page) + 1,
                        "enabled": $scope.size
                    };
                    $scope.btn = [];
                    for (var i = page - 2; i < page + 3; i++) {
                        if (i > 0) {
                            if (Math.ceil(response.data.tasks.length / 10) >= i) {
                                var item = {
                                    "page": i,
                                    "active": i == page
                                };
                                $scope.btn.push(item);
                            }
                        }
                    }
                });
        }
    });
}]);

taskApp.controller("tasks.pagination",["$scope", "$http", "tokenCheck", "pathStarter", function($scope, $http, tokenCheck, pathStarter){
    tokenCheck.tokenCheck().success(function(response){
        var page = getParameterByName("page");
        if(getParameterByName("page") == ""){
            page = 1;
        }

        $http({method: "GET", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, REQUEST: "task_info", USER_ID: response.USER_ID, DATA: ["id"]}, paramSerializer: "$httpParamSerializerJQLike"})
            .then(function(response){
                $scope.size = Math.ceil(response.data.tasks.length/10) >= parseInt(page)+1;
                $scope.back = {
                    "page": parseInt(page)-1,
                    "enabled": parseInt(page)-1>0
                };
                $scope.next = {
                    "page": parseInt(page)+1,
                    "enabled": $scope.size
                };
                $scope.btn = [];
                for(var i = page - 2; i < page + 3; i++){
                    if(i > 0){
                        if(Math.ceil(response.data.tasks.length/10) >= i){
                            var item = {
                                "page": i,
                                "active": i == page
                            };
                            $scope.btn.push(item);
                        }
                    }
                }
            });
    });
}]);

taskApp.controller("task.info", ["$window", "$scope", "$http", "tokenCheck", "pathStarter", function ($window, $scope, $http, tokenCheck, pathStarter) {
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID){
            var task = getParameterByName("task");
            if(task != "") {

                $http({method: "GET", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, REQUEST: "task_info", USER_ID: response.USER_ID, TASK_ID: task, DATA: ["name", "due", "description"]}, paramSerializer: "$httpParamSerializerJQLike"})
                    .success(function (response2) {
                        $scope.task = response2.task;

                        $http({method: "GET", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, REQUEST: "user_info", USER_ID: response.USER_ID, TASK_ID: task, DATA: ["status", "comment"]}, paramSerializer: "$httpParamSerializerJQLike"})
                            .success(function (response3) {
                                $scope.task.status = response3.info.status;
                                $scope.task.comment = response3.info.comment;
                            });
                    });

                $scope.submitReview = function(){
                    //TODO Make sure submit can't happen unless files have been submitted
                    $http({method: "POST", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, REQUEST: "status", USER_ID: response.USER_ID, TASK_ID: task}, data: {status: "Pending", comment: ""}})
                        .success(function(response1){
                            $window.location.reload();
                        })
                }
            }
        }
    });
}]);

taskApp.controller("task.user_files", ["$scope", "$http", "tokenCheck", "pathStarter", "Upload", function($scope, $http, tokenCheck, pathStarter, Upload){
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID){
            var task = getParameterByName("task");
            if(task != "") {
                $scope.files = [];

                $http({method: "GET", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, REQUEST: "user_files", USER_ID: response.USER_ID, TASK_ID: task, DATA: ["id", "path", "original_name", "size"]}, paramSerializer: "$httpParamSerializerJQLike"})
                    .success(function (response2) {
                        if(response2.files) {
                            $scope.delete = function (id) {
                                $http({
                                    method: "DELETE",
                                    url: pathStarter + "api/task.php",
                                    params: {TOKEN: response.TOKEN, FILE_ID: id, FILE_TYPE: "user"}
                                })
                                    .success(function () {
                                        angular.element("#" + id).remove();
                                    });
                            }
                            $scope.files = response2.files;
                        }
                    });

                $scope.submit = function(){
                    console.log($scope.file);
                    if($scope.file && !$scope.file.$error){
                        $scope.upload($scope.file);
                    }
                };

                $scope.upload = function(file){
                    Upload.upload({
                        url: pathStarter+'api/task.php?TOKEN='+response.TOKEN+"&REQUEST=file&TASK_ID="+task+"&USER_ID="+response.USER_ID,
                        file: file
                    }).success(function (data, status, headers, config) {
                        document.forms["form"].reset();
                        $scope.files.push(data.file);
                        }).error(function(){
                        console.log("Error");
                    });
                }
            }
        }
    });
}]);

/*taskApp.controller('task.uploadFile', ["$scope", "$http", "tokenCheck", "Upload", "pathStarter", function ($scope, $http, tokenCheck, Upload, pathStarter) {
    tokenCheck.tokenCheck().success(function(response) {
        if(response.VALID) {
            var task = getParameterByName("task");
            if(task != "") {
                $scope.submit = function(){
                    console.log($scope.file);
                    if($scope.file && !$scope.file.$error){
                        $scope.upload($scope.file)
                    }
                }

                $scope.upload = function(file){
                    Upload.upload({
                        url: pathStarter+'api/task.php?TOKEN='+response.TOKEN+"&REQUEST=file&TASK_ID="+task+"&USER_ID="+response.USER_ID,
                        file: file
                    }).success(function (data, status, headers, config) {
                        document.forms["form"].reset();
                        angular.element("#uploadedFiles").prepend('<div class="col-md-6" ng-repeat="x in files" id="'+data.file.id+'"> <div class="well well-sm" style="height: 60px;"> <a href="../'+data.file.path+'" download="'+data.file.original_name+'"> <div class="row"> <div class="col-xs-2"> <i class="fa fa-3x fa-file-o"></i> </div> <div class="col-xs-10">'+data.file.original_name+'<br> <small class="text-muted">File Size: '+data.file.size+'</small> </div> </div> </a><a style="position: absolute;right: 25px;top:8px;" ng-show="task.status != \'Accepted\'" ng-click="delete(x.id)" class="pull-right"><i class="fa fa-times"></i></a> </div> </div>');
                        //$("#uploadedFiles").prepend('<div class="col-md-6" ng-repeat="x in files" id="'+data.file.id+'"><div class="well well-sm"><div class="row"><a ng-href="../'+data.file.path+'" download="'+data.file.original_name+'"><div class="col-xs-2"><i class="fa fa-3x fa-file-word-o"></i></div></a> <div class="col-xs-10"> <a ng-click="delete('+data.file.id+')" class="pull-right fa fa-times"></a> <a ng-href="../'+data.file.path+'" download="'+data.file.original_name+'">'+data.file.original_name+'<br><small class="text-muted">File Size: '+data.file.size+'</small></a> </div> </div> </div> </div>');
                    })
                }
            }
        }
    });
}]);*/

/*taskApp.controller('task.admin.uploadFile', ["$scope", "$http", "tokenCheck", "Upload", "pathStarter", function ($scope, $http, tokenCheck, Upload, pathStarter) {
    tokenCheck.tokenCheck().success(function(response) {
        if(response.VALID) {
            var task = getParameterByName("task");
            if(task != "") {
                $scope.submit = function(){
                    if($scope.file && !$scope.file.$error){
                        $scope.upload($scope.file)
                    }
                }

                $scope.upload = function(file){
                    var name = angular.element("[name='uploadName']").val();
                    var description = angular.element("[name='uploadDescription']").val();
                    Upload.upload({
                        url: pathStarter+'api/task.php?TOKEN='+response.TOKEN+"&REQUEST=file&TASK_ID="+task,
                        file: file,
                        fields: {name: name, description: description}
                    }).success(function (data, status, headers, config) {
                        console.log(data);
                        document.forms["uploadForm"].reset();
                        //angular.element("#uploadedFiles").prepend('<div class="well well-sm" ng-repeat="x in files"> <a href="../../'+data.file.path+'" download> <div class="row"> <div class="col-xs-2 text-black"> <span class="fa-stack fa-lg"> <i class="fa fa-file-o fa-stack-2x"></i> <i class="fa fa-arrow-down fa-stack-1x" style="top: 3px;"></i> </span> </div> <div class="col-xs-10 text-black">'+data.file.name+'<br> <small class="text-muted">'+data.file.description+'</small> </div> </div> </a> </div>');
                        //angular.element("#uploadedFiles").prepend('<div class="col-md-6" ng-repeat="x in files" id="'+data.file.id+'"> <div class="well well-sm" style="height: 60px;"> <a href="../'+data.file.path+'" download="'+data.file.original_name+'"> <div class="row"> <div class="col-xs-2"> <i class="fa fa-3x fa-file-o"></i> </div> <div class="col-xs-10">'+data.file.original_name+'<br> <small class="text-muted">File Size: '+data.file.size+'</small> </div> </div> </a><a style="position: absolute;right: 25px;top:8px;" ng-show="task.status != \'Accepted\'" ng-click="delete(x.id)" class="pull-right"><i class="fa fa-times"></i></a> </div> </div>');
                    })
                }
            }
        }
    });
}]);*/

taskApp.controller("task.files", ["$scope", "$http", "tokenCheck", "pathStarter", function($scope, $http, tokenCheck, pathStarter){
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID){
            var task = getParameterByName("task");
            if(task != ""){
                $http({method: "GET", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, TASK_ID: task, REQUEST: "task_files", DATA: ["path", "name", "description"]}, paramSerializer: "$httpParamSerializerJQLike"})
                    .success(function(response1){
                        $scope.files = response1.files;
                    });
            }
        }
    });
}]);

taskApp.controller("task.admin.files", ["$scope", "$http", "tokenCheck", "pathStarter", "Upload", function($scope, $http, tokenCheck, pathStarter, Upload){
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID){
            var task = getParameterByName("task");
            if(task != ""){
                $scope.files = [];

                $http({method: "GET", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, TASK_ID: task, REQUEST: "task_files", DATA: ["id", "path", "name", "description"]}, paramSerializer: "$httpParamSerializerJQLike"})
                    .success(function(response2){
                        if(response2.files) {
                            $scope.delete = function (id) {
                                $http({
                                    method: "DELETE",
                                    url: pathStarter + "api/task.php",
                                    params: {TOKEN: response.TOKEN, FILE_ID: id, FILE_TYPE: "task"}
                                })
                                    .success(function () {
                                        angular.element("#" + id + "-taskFile").remove();
                                    })
                            }
                            $scope.files = response2.files;
                        }
                    });

                $scope.submit = function(){
                    console.log($scope.file);
                    if($scope.file && !$scope.file.$error){
                        $scope.upload($scope.file)
                    }
                };

                $scope.upload = function(file){
                    var name = angular.element("[name='uploadName']").val();
                    var description = angular.element("[name='uploadDescription']").val();
                    if(name != "" && description != "") {
                        Upload.upload({
                            url: pathStarter + 'api/task.php?TOKEN=' + response.TOKEN + "&REQUEST=file&TASK_ID=" + task,
                            file: file,
                            fields: {name: name, description: description}
                        }).success(function (data, status, headers, config) {
                            document.forms["uploadForm"].reset();
                            $scope.files.push(data.file);
                        })
                    }
                }
            }
        }
    });
}]);

taskApp.controller("task.admin.assign", ["$scope", "$http", "tokenCheck", "pathStarter", function($scope, $http, tokenCheck, pathStarter){
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID){
            var task = getParameterByName("task");
            if(task != ""){
                $scope.toAssign = [];
                $scope.toUnassign = [];

                $http({method: "GET", url: pathStarter+"api/user.php", params: {TOKEN: response.TOKEN, PERMISSION: 2, DATA: ["id"]}, paramSerializer: "$httpParamSerializerJQLike"})
                    .success(function(response1){
                        $scope.assigned = [];
                        $scope.unassigned = [];

                        for(var i = 0; i < response1.users.length; i++){
                            $http({method: "GET", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, REQUEST: "assigned", USER_ID: response1.users[i].id, TASK_ID: task}})
                                .success(function (response2) {
                                    $http({method: "GET", url: pathStarter+"api/user.php", params: {TOKEN: response.TOKEN, PERMISSION: 2, USER_ID: response2.user_id, DATA: ["id", "student_id", "first_name", "last_name"]}, paramSerializer: "$httpParamSerializerJQLike"})
                                        .success(function(response3){
                                            if (response2.assigned) {
                                                $scope.assigned.push(response3);
                                            }else{
                                                $scope.unassigned.push(response3);
                                            }
                                        });
                                });
                        }
                    });

                $scope.unassign = function(id, add){
                    if(add){
                        if($scope.toUnassign.indexOf(id) == -1) {
                            $scope.toUnassign.push(id);
                        }
                    }else {
                        if($scope.toUnassign.indexOf(id) != -1) {
                            $scope.toUnassign.splice($scope.toUnassign.indexOf(id), 1);
                        }
                    }
                    /*for(var i = 0; i < $scope.assigned; i++){
                        if($scope.assigned[i].id = id){
                            $scope.assigned.push($scope.unassigned[i]);
                            $scope.unassigned.splice(i, 1);
                            break;
                        }
                    }*/
                };

                $scope.assign = function (id, add) {
                    if(add){
                        if($scope.toAssign.indexOf(id) == -1) {
                            $scope.toAssign.push(id);
                        }
                    }else {
                        console.log($scope.toAssign.indexOf(id));
                        if($scope.toAssign.indexOf(id) != -1) {
                            $scope.toAssign.splice($scope.toAssign.indexOf(id), 1);
                        }
                    }
                };

                $scope.assignButton = function(){
                    for(var i = 0; i < $scope.toAssign.length; i++){
                        for(var k = 0; k < $scope.unassigned.length; k++){
                            if($scope.unassigned[k].id == $scope.toAssign[i]){
                                $http({method: "PUT", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, REQUEST: "assign", USER_ID: $scope.unassigned[k].id, TASK_ID: task}, data:{enabled: 1}})
                                    .success(function(response){
                                        console.log(response);
                                        $scope.assigned.push($scope.unassigned[k]);
                                        $scope.unassigned.splice(k, 1);
                                    });
                                break;
                            }
                        }
                    }
                    $scope.toAssign = [];
                    $("#unassignedSelector").iCheck("Uncheck");
                }

                $scope.unassignButton = function(){
                    for(var i = 0; i < $scope.toUnassign.length; i++){
                        for(var k = 0; k < $scope.assigned.length; k++){
                            if($scope.assigned[k].id == $scope.toUnassign[i]){
                                $http({method: "PUT", url: pathStarter+"api/task.php", params: {TOKEN: response.TOKEN, REQUEST: "assign", USER_ID: $scope.assigned[k].id, TASK_ID: task}, data:{enabled: 0}})
                                    .success(function(){
                                        $scope.unassigned.push($scope.assigned[k]);
                                        $scope.assigned.splice(k, 1);
                                    });
                                break;
                            }
                        }
                    }
                    $scope.toUnassign = [];
                    $("#assignedSelector").iCheck("Uncheck");
                    console.log($scope.toUnassign);
                    console.log($scope.toAssign);
                }
            }
        }
    })
}]);

taskApp.directive("ngAssignon", function(){
    return function (scope, element, attrs) {
        $(element)
            .on("ifChecked", function(event){
                scope.unassign(parseInt(event.target.id.split("-")[0]), true);
            })
            .on("ifUnchecked", function(event){
                scope.unassign(parseInt(event.target.id.split("-")[0]), false);
            });
    }
});

taskApp.directive("ngUnassignon", function(){
    return function (scope, element, attrs) {
        $(element)
            .on("ifChecked", function(event){
                scope.assign(parseInt(event.target.id.split("-")[0]), true);
            })
            .on("ifUnchecked", function(event){
                scope.assign(parseInt(event.target.id.split("-")[0]), false);
            });
    }
});

taskApp.directive("ngIselect", function(){
    return function(scope, element, attrs){
        angular.element(element).iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: "iradio_square-green",
            increaseArea: '20%'
        });
    }
})

taskApp.filter("dueFormat", function($filter){
    return function(text){
        if(text != null) {
            var tempdate = new Date(text.replace(/-/g, "/"));
            return $filter('date')(tempdate, "MMM-dd-yyyy");
        }else{
            return "";
        }
    }
});

taskApp.filter("dueFormat2", function($filter){
    return function(text){
        if(text != null) {
            var tempdate = new Date(text.replace(/-/g, "/"));
            return $filter('date')(tempdate, "yyyy-MM-dd");
        }else{
            return "";
        }
    }
});

//////////////////////////////////////////////////////////
/////                User Controllers                /////
//////////////////////////////////////////////////////////
taskApp.controller("users.admin", ["$scope", "$http", "tokenCheck", "$window", "pathStarter", function ($scope, $http, tokenCheck, $window, pathStarter){
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID && response.PERMISSION < 2){
            $scope.usersMode = getParameterByName("user") == "";
        }else{
            $window.location.href = pathStarter+"login/";
        }
    });
}]);

taskApp.controller("users.admin.list", ["$scope", "$http", "pathStarter", "tokenCheck", function ($scope, $http, pathStarter, tokenCheck) {
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID){
            var page = (getParameterByName("page") - 1) * 15;
            if (getParameterByName("page") == "") {
                page = 0;
            }

            $scope.loadData = function(){
                $http({method: "GET", url: pathStarter+"api/user.php", params: {TOKEN: response.TOKEN, DATA: ["id", "student_id", "first_name", "last_name", "permission", "status"]}, paramSerializer: "$httpParamSerializerJQLike"})
                    .success(function (response1) {
                        $scope.users = response1.users;
                    });
            };

            $scope.createUser = function () {
                if ($scope.user != null) {
                    if ($scope.user.fname != null && $scope.user.lname != null && $scope.user.permission != null) {
                        if($scope.user.password == null){
                            $scope.user.password = "";
                        }

                        $http({method: "POST", url: pathStarter + "api/user.php", params: {TOKEN: response.TOKEN}, data: {FIRST_NAME: $scope.user.fname, LAST_NAME: $scope.user.lname, PASSWORD: $scope.user.password, PERMISSION: $scope.user.permission}})
                            .success(function (response1) {
                                document.forms["addUser"].reset();
                                $http({method: "GET", url: pathStarter+"api/user.php", params: {TOKEN: response.TOKEN, USER_ID: response1.student.user_id, DATA: ["id", "student_id", "first_name", "last_name", "permission"]}, paramSerializer: "$httpParamSerializerJQLike"})
                                    .success(function(response2){
                                        console.log(response2);
                                        $scope.users.push(response2);
                                    });
                            });
                    }
                }
            };

            $scope.loadData();
        }
    });
}]);

taskApp.controller("users.admin.edit", ["$scope", "$http", "pathStarter", "tokenCheck", function($scope, $http, pathStarter, tokenCheck){
    tokenCheck.tokenCheck().success(function(response){
        if(response.VALID){
            var user_id = getParameterByName("user");

            $http({method: "GET", url: pathStarter+"api/user.php", params: {TOKEN: response.TOKEN, USER_ID: user_id, DATA: ["first_name", "last_name", "email", "phone_number", "address", "status", "permission"]}, paramSerializer: "$httpParamSerializerJQLike"})
                .success(function(response1){
                    console.log(response1);
                    $scope.user = response1;
                });

            $scope.updateInfo = function(){
                if($scope.user.edit != null) {
                    var first_name;
                    var last_name;
                    var email;
                    var phone_number;
                    var address;
                    var permission;
                    if ($scope.user.edit.first_name == null)
                        first_name = $scope.user.first_name;
                    else
                        first_name = $scope.user.edit.first_name;
                    if ($scope.user.edit.last_name == null)
                        last_name = $scope.user.last_name;
                    else
                        last_name = $scope.user.edit.last_name;
                    if ($scope.user.edit.email == null)
                        email = $scope.user.email;
                    else
                        email = $scope.user.edit.email;
                    if ($scope.user.edit.phone_number == null)
                        phone_number = $scope.user.phone_number;
                    else
                        phone_number = $scope.user.edit.phone_number;
                    if ($scope.user.edit.address == null)
                        address = $scope.user.address;
                    else
                        address = $scope.user.edit.address;
                    if ($scope.user.edit.permission == null)
                        permission = $scope.user.permission;
                    else
                        permission = $scope.user.edit.permission;

                    $http({
                        method: "PUT",
                        url: pathStarter + "api/user.php",
                        params: {TOKEN: response.TOKEN, USER_ID: user_id},
                        data: {
                            DATA: {
                                first_name: first_name,
                                last_name: last_name,
                                email: email,
                                phone_number: phone_number,
                                address: address,
                                permission: permission
                            }
                        }
                    })
                        .success(function (response1) {
                            console.log(response1);
                        })
                        .error(function(response1){
                            console.log(response1);
                        });
                }
            }
        }
    });
}]);

//////////////////////////////////////////////////////////
/////                Blog Controllers                /////
//////////////////////////////////////////////////////////

/**
 * Blog posts Controller
 */
taskApp.controller("blog.posts", ["$scope", "$http", "pathStarter", function($scope, $http, pathStarter){
    var page = (getParameterByName("page")-1)*5;
    if(getParameterByName("page") == ""){
        page = 0;
    }

    var category = null;
    if(getParameterByName("category") != ""){
        category = getParameterByName("category");
    }

    $http({method: "GET", url: pathStarter+"api/blog.php", params: {LIMIT: 5, PAGE: page, CATEGORY: category}})
        .then(function(response){
            $scope.posts = response.data.posts;
        });
}]);

/**
 * Blog Categories Controller
 */
taskApp.controller("blog.categories", ["$scope", "$http", "pathStarter", function($scope, $http, pathStarter){
    var category = getParameterByName("category");

    $scope.clear = false;

    $http({method: "GET", url: pathStarter+"api/category.php"})
        .then(function(response){
            $scope.categories = response.data.categories;

            for(var i = 0; i < $scope.categories.length; i++){
                $scope.categories[i].active = $scope.categories[i].id == category;
                if($scope.clear == false) {
                    $scope.clear = $scope.categories[i].id == category;
                }
            }
        });
}]);

/**
 * Blog Pagination Controller
 */
taskApp.controller("blog.pagination", ["$scope", "$http", "pathStarter", function($scope, $http, pathStarter){
    $scope.param = "";

    var page = getParameterByName("page");
    if(getParameterByName("page") == ""){
        page = 1;
    }

    var category = null;
    if(getParameterByName("category") != ""){
        category = getParameterByName("category");
        $scope.param = $scope.param+"category="+getParameterByName("category");
    }

    $http({method: "GET", url: pathStarter+"api/blog.php", params: {DATA: ["id"], CATEGORY: category}, paramSerializer: "$httpParamSerializerJQLike"})
        .then(function(response){
            $scope.size = Math.ceil(response.data.posts.length/5) >= parseInt(page)+1;
            $scope.back = {
                "page": parseInt(page)-1,
                "enabled": parseInt(page)-1>0
            };
            $scope.next = {
                "page": parseInt(page)+1,
                "enabled": $scope.size
            };
            $scope.btn = [];
            for(var i = page - 2; i < page + 3; i++){
                if(i > 0){
                    if(Math.ceil(response.data.posts.length/5) >= i){
                        var item = {
                            "page": i,
                            "active": i == page
                        };
                        $scope.btn.push(item);
                    }
                }
            }
        });
}]);