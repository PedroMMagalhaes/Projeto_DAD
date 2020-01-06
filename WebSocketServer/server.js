/*jshint esversion: 6 */

var app = require("http").createServer();

// Se tiverem problemas com "same-origin policy" deverão activar o CORS.

// Aqui, temos um exemplo de código que ativa o CORS (alterar o url base)

// var app = require('http').createServer(function(req,res){
// Set CORS headers
//  res.setHeader('Access-Control-Allow-Origin', 'http://---your-base-url---');
//  res.setHeader('Access-Control-Request-Method', '*');
//  res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
//  res.setHeader('Access-Control-Allow-Credentials', true);
//  res.setHeader('Access-Control-Allow-Headers', req.header.origin);
//  if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
//      res.writeHead(200);
//      res.end();
//      return;
//  }
// });

// NOTA: A solução correta depende da configuração do próprio servidor,
// e alguns casos do próprio browser.
// Assim sendo, não se garante que a solução anterior funcione.
// Caso não funcione é necessário procurar/investigar soluções alternativas

var io = require("socket.io")(app);

var LoggedUsers = require("./loggedusers.js");

var GameList = require("./gamelist.js");

app.listen(8080, function() {
  console.log("listening on *:8080");
});

// ------------------------
// Estrutura dados - server
// ------------------------

// loggedUsers = the list (map) of logged users.
// Each list element has the information about the user and the socket id
// Check loggedusers.js file

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