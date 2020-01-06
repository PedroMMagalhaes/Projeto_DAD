/*jshint esversion: 6 */

class LoggedUsers {
  constructor() {
    this.users = new Map();
  }

  userInfoByID(id) {
    for (var [userID, userInfo] of this.users) {
      if (userInfo.user.id == id) {
        return userInfo;
      }
    }
    return null;
  }

  userInfoByEmail(email) {
    for (var [userID, userInfo] of this.users) {
      if (userInfo.user.email == email) {
        return userInfo;
      }
    }
    return null;
  }

  userInfoBySocketID(socketID) {
    for (var [userID, userInfo] of this.users) {
      if (userInfo.socketID == socketID) {
        return userInfo;
      }
    }
    return null;
  }

  addUserInfo(user, socketID) {
    let userInfo = { user: user, socketID: socketID };
    this.users.set(user.id, userInfo);
    return userInfo;
  }

  removeUserInfoByID(userID) {
    let userInfo = this.userInfoByID(userID);
    if (userInfo === null) {
      return null;
    }
    let cloneUserInfo = Object.assign({}, userInfo);
    this.users.delete(userID);
    return cloneUserInfo;
  }

  removeUserInfoBySocketID(socketID) {
    let userInfo = this.userInfoBySocketID(socketID);
    if (userInfo === null) {
      return null;
    }
    let cloneUserInfo = Object.assign({}, userInfo);
    this.users.delete(userInfo.user.id);
    return cloneUserInfo;
  }

}

module.exports = LoggedUsers;
