//------------------------ Base API URL ------------------------//
export const baseApiUrl = "http://localhost:5014/api/";

//------------------------ Friends ------------------------//
export const getFriendsURL =  baseApiUrl + "Friends";

export const getUserFriendsURL =  baseApiUrl + "Friends/getUsersFriends/{userId}";

export const getFriendsRequestsURL =  baseApiUrl + "Friends/requests/{userId}";

export const getFriendsSearchURL =  baseApiUrl + "Friends/Search";