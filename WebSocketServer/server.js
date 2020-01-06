/*jshint esversion: 6 */

var app = require("http").createServer();

var io = require("socket.io")(app);

var LoggedUsers = require("./loggedusers.js");

var GameList = require("./gamelist.js");

app.listen(8080, function() {
  console.log("listening on *:8080");
});

let loggedUsers = new LoggedUsers();

io.on("connection", function(socket) {
  console.log("client has connected (socket ID = " + socket.id + ")");


  socket.on("user_enter", function(user) {
    if (user) {
      loggedUsers.addUserInfo(user, socket.id);
      console.log(user);
    }
  });
  socket.on("user_exit", function(user) {
    if (user) {
      loggedUsers.removeUserInfoByID(user.id);
      console.log("exited")
    }
  });

  socket.on("transfer", function(msg, sourceUser, destUser) {
    let userInfo = loggedUsers.userInfoByEmail(destUser.email);
    let socket_id = userInfo !== undefined ? userInfo.socketID : null;
    if (socket_id === null) {
      socket.emit("privateMessage_unavailable", destUser);
    } else {
      //console.log(loggedUsers.removeUserInfoByID(sourceUser));
      io.to(socket_id).emit("transfer", {user:loggedUsers.userInfoByID(sourceUser.id),value:msg.value});
      socket.emit("privateMessage_sent", msg, destUser);
      console.log("enviado")
    }
  });

});