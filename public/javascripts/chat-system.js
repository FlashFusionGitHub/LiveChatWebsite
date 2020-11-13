
var group_id = '';

var loadChat;
var loadGroups;
var loadLobby;
var loadFriends;

StartLoadingFriendsListFunction();
StartLoadingGroupChatsFunction();

$(document).on('click', 'button[id="add-to-chat"]', function() {
    var add_to_chat_array = {
        "user_id": this.value,
        "group_id": group_id
    }
    $.ajax ({
        url: "/home/index/?add_user_to_chat="+ JSON.stringify(add_to_chat_array),
        success: function(result) {

            if(result != null) {
                reloadFriendsList = true;
            }
        }
    });
});

$(document).on('click', 'button[id="remove-from-chat"]', function() {
    var remove_from_chat_array = {
        "user_id": this.value,
        "group_id": group_id
    }
    $.ajax ({
        url: "/home/index/?remove_user_from_chat="+ JSON.stringify(remove_from_chat_array),
        success: function(result) {
            
            reloadLobbyList = true;
            reloadFriendsList = true;
        }      
    });
});

$(document).on('click', 'button[id="chat-group-btn"]', function() {

    StopLoadingChat();
    StartLoadingChat();
    StopLoadingLobbyMembersFunction();
    StartLoadingLobbyMembersFunction();
    group_id = this.value;
    reloadLobbyList = true;
    reloadFriendsList = true;

    $.ajax ({
    url: "/home/index/?group_chat="+ group_id,
    success: function(result) {
        var chat_window = '';
        var obj = JSON.parse(result)['chat-window'];
        if(obj != null) {
            obj.forEach(myFunction);
            function myFunction(item, index) {
                
                chat_window += '<div class="username">' +
                '<b>' +  item.username + '</b>' +
                '</div>' +
                item.message +
                '<div class="date-time-info">' +
                item.posted +
                '</div>' +
                '<hr>'
            }
            $("#chat-window").html(chat_window);
        }
        else {
            $("#chat-window").html('There are no messages yet!');
        }
        $("#message-box").attr('class', 'message-box');
        $("#message-box").html (
            '<form id="message-form" method="post" class="row mx-0">' +
            '<div class="col-10 mt-1 px-1">' +
            '<textarea class="form-control" name="message" rows="3" placeholder="Remember, be nice and have fun!"></textarea>' +
            '</div>' +
            '<div class="col-2 mt-1 px-1">' +
            '<button type="submit" name="send-message-button" id="send-message-button" value="" class="message-btn btn">Send</button>' +
            '</div>' +
            '</form>'
        );
        $('#message-form').submit(function(event) {
            
            var formData = {
                "chat_id" : $('button[name=send-message-button]').val(),
                "message" : $('textarea[name=message]').val()
            }
            $.ajax({
                type : 'post',
                url : '/home/index/?message_form=' + JSON.stringify(formData)
            }).done(function(data) {
                $('textarea[name=message]').val('');
            });
            event.preventDefault();
        });
        $("#send-message-button").attr('value', group_id);
            loadFriendsList();
        }
    });
});


$(document).on('click', 'button[id="leave-chat-group-btn"]', function() {
    $.ajax ({
        url: "/home/index/?leave-chat-group="+ this.value,
        success: function(result) {
            if(result != null)
                reloadFriendsList = true;
        }
    });
});

function loadChatWindow() {
    $.ajax({
        url: "/home/index/?messages="+ group_id,
        success: function(result) {
            var chat_group = JSON.parse(result)["group_chat"];
            var chat = JSON.parse(result)["chat"];
            var chat_group_member = JSON.parse(result)["chat_group_member"];
            if(chat_group_member != false) {
                if(chat_group != null) {
                    var chat_window = '';
                    if(chat != null) {
                    
                        chat.forEach(myFunction);
                    
                        function myFunction(item, index) {
                        
                            chat_window += '<div class="username">' +
                            '<b>' +  item.username + '</b>' +
                            '</div>' +
                            item.message +
                            '<div class="date-time-info">' +
                            item.posted +
                            '</div>' +
                            '<hr>'
                        }
                    
                        $("#chat-window").html(chat_window);
                    }
                    else {
                    
                        $("#chat-window").html('There are no messages yet!');
                    }
                }
            }
            else {
              
                StopLoadingChat();
                $("#chat-window").html('Open a lobby and start chatting');
                $("#message-box").html('');
                $("#message-box").attr('class', '');  
            }
        }
    });
}

var loadGroupChatsObj;
var previousLoadGroupChatsObj = 0;

function loadGroupChats() {
    $.ajax({
        url: "/home/index/?chat-groups-list=1",
        success: function(result) {
            loadGroupChatsObj = JSON.parse(result);
            if(loadGroupChatsObj.length != previousLoadGroupChatsObj.length) {
                var chat_groups_list = '';
                if(loadGroupChatsObj.length > 0) {
                    loadGroupChatsObj.forEach(myFunction);
                    function myFunction(item, index) {
                        chat_groups_list += '<li class="chat-group-list-object">' +
                        '<button class="chat-group-button" id="chat-group-btn" name="chat-group-btn" value="' + item.group_id + '"> ' + item.group_name + ' </button>' +
                        '<button onclick="hideButton(this)" class="chat-group-leave-button btn btn-sm btn-warning" id="leave-chat-group-btn" value="' + item.group_id + '">Exit</button>' +
                        '</li>';
                    }
                    $("#active-chats-list").html(chat_groups_list);
                }
                else {
                    $("#active-chats-list").html(
                        '<li class="list-group-item">No active chats</li>'
                    );
                }
                previousLoadGroupChatsObj = loadGroupChatsObj;
            }
        }
    });
}

var friendsListObj = null;
var friendsRequestsObj = null;
var button = '';
var previousFriendsListObj = [];
var previousFriendsRequestsObj = [];
var reloadFriendsList;

function loadFriendsList() {
    $.ajax({
        url: "/home/index/?friends-list=" + group_id,
        success: function(result) {

            friend_requests = '';
            friends_list = '';
            friendsListObj = JSON.parse(result)["friends"];
            friendsRequestsObj = JSON.parse(result)["requests"];
            chatGroupMemberObj = JSON.parse(result)["group_member"];

            if(friendsListObj != null) {
                if(previousFriendsListObj.length != friendsListObj.length) {
                    reloadFriendsList = true;
                }
            }

            if(friendsRequestsObj != null) {
                if(previousFriendsRequestsObj.length != friendsRequestsObj.length) {
                    reloadFriendsList = true;
                }
            }

            if(reloadFriendsList == true) {

                if(friendsRequestsObj != null) {

                    friendsRequestsObj.forEach(myFunction4);
                    function myFunction4(item, index) {
                        friend_requests += '<form class="list-group-item" method="post">' +
                        '<a href="/profile/user/' + item.name + '" class="link-unstyled">' +
                        '<h6>Request Pending</h6>' +
                        '<span class="image-cropper profile-img">' +
                        '<img class="profile-pic" src="' + item.image  + '" alt="' + item.image  + ' width="50" height="50">' +
                        '</span>' +
                        '<span class="profile-username">' + item.name + '</span>' +
                        '</a>' +
                        '<br><br>' +
                        '<button onclick="hideButton(this)" name="declineBtn" class="request-btn btn btn-sm btn-danger ml-2" type="submit" value="' + item.id + '">Decline</button>' +
                        '<button onclick="hideButton(this)" name="acceptBtn" class="request-btn btn btn-sm btn-success" type="sumbit" value="' + item.id + '">Accept</button>' +
                        '</form>'
                    }
                }

                if(friendsListObj != null) {
                    friendsListObj.forEach(myFunction3);
                    function myFunction3(item, index) {
                    
                        friends_list += '<li class="list-group-item" method="post" name="add-to-chat-form" value="">' +
                        '<a href="/profile/user/' + item.name + '" class="link-unstyled">' +
                        '<span class="image-cropper profile-img">' +
                        '<img class="profile-pic" src="' + item.image  + '" alt="' + item.image  + ' width="50" height="50">' +
                        '</span>' +
                        '<span class="profile-username">' +
                        item.name +
                        '</span>' +
                        '</a>' +
                        '<br>';
                        if(chatGroupMemberObj != false) {
                            if(item.joined == false) {
                                button = '<button onclick="hideButton(this)" name="add-to-chat" id="add-to-chat" class="request-btn btn btn-sm btn-primary ml-2" type="submit" value="' + item.user_id + '">Add To Chat</button>';
                            }
                            else {
                                button = '';
                            }
                        }
                        else {
                            button = '';
                        }

                        friends_list += button += '</li>';
                    }
                }

                if(friendsListObj != null && friendsRequestsObj != null) {
                    $("#friends-list").html(friend_requests += friends_list);
                }
                else if(friendsListObj == null && friendsRequestsObj != null) {
                    $("#friends-list").html(friend_requests += '<li class="list-group-item">Chatting is best enjoyed with friends</li>');
                }
                else if(friendsListObj != null && friendsRequestsObj == null) {
                    $("#friends-list").html(friends_list);
                }
                else if(friendsListObj == null && friendsRequestsObj == null) {
                    $("#friends-list").html('<li class="list-group-item">Chatting is best enjoyed with friends</li>');
                }

                if(friendsListObj != null)
                    previousFriendsListObj = friendsListObj;

                if(friendsRequestsObj != null)
                    previousFriendsRequestsObj = friendsRequestsObj;

                reloadFriendsList = false;
            }    
        }
    });
}

var reloadLobbyList;
var loadLobbyListObj;
var previousLoadLobbyListObj = 0;

function loadLobbyMembers() {
    $.ajax({
        url: "/home/index/?lobby-list=" + group_id,
        success: function(result) {
            if(JSON.parse(result)["group_member"] != false) {
                if(JSON.parse(result)["lobby_members"] != null && JSON.parse(result)["lobby_creator"] != null) {
                    var loadLobbyListObj = JSON.parse(result)["lobby_members"];
                    var creator_obj = JSON.parse(result)["lobby_creator"];
                    if(loadLobbyListObj.length != previousLoadLobbyListObj.length || reloadLobbyList == true) {
                    
                        lobby_list = '';
                        lobby_button = '';
                        loadLobbyListObj.forEach(myFunction2);
                        function myFunction2(item, index) {
                            lobby_list += 
                            '<li class="members-list-item">' +
                            '<div class="lobby-member-name">' + item.username + '</div>';
                            if(item.creator != true) {
                                if(creator_obj == true) {
                                    lobby_button = '<button onclick="hideButton(this)" class="remove-from-group-button btn btn-sm btn-danger" id="remove-from-chat" value="' + item.user_id +'">Boot</button>';
                                }
                                else {
                                    lobby_button = '';
                                }
                            }
                            else {
                                lobby_button = '<span class="badge badge-success">Owner</span>';
                            }
                        
                            lobby_list += lobby_button += '</li>';
                        }
                    
                        $("#lobby-members-list").html(lobby_list);
                        previousLoadLobbyListObj = loadLobbyListObj;
                        reloadLobbyList = false;
                    }
                }
                else {
                    
                    $("#lobby-members-list").html('<li class="members-list-item">No members here</li>');
                }
            }
            else {
                $("#lobby-members-list").html('<li class="members-list-item">No members here</li>');
            }
        }
    });
}

function StopLoadingFriendsListFunction() {
    clearInterval(loadFriends);
}
function StartLoadingFriendsListFunction() {
    loadFriends = setInterval(loadFriendsList, 1000);
}
function StopLoadingLobbyMembersFunction() {
    clearInterval(loadLobby);
}
function StartLoadingLobbyMembersFunction() {
    loadLobby = setInterval(loadLobbyMembers, 1000);
}
function StopLoadingGroupChatsFunction() {
    clearInterval(loadGroups);
}
function StartLoadingGroupChatsFunction() {
    loadGroups = setInterval(loadGroupChats, 1000);
}
function StopLoadingChat() {
    clearInterval(loadChat);
}
function StartLoadingChat() {
    loadChat = setInterval(loadChatWindow, 1000);
}
function hideButton(input) {
    input.style.display = "none"
}