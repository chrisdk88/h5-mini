//------------------------ Base API URL ------------------------//
export const baseApiUrl = "http://localhost:5014/api/";

//------------------------ Users ------------------------//
export const getUsersURL = baseApiUrl + "Users";

export const getUsersIdURL = baseApiUrl + "Users/{userId}";

export const getUsersExpLvlURL = baseApiUrl + "Users/GetUsersExpAndLevel/{userId}";

export const getUsersAdminURL = baseApiUrl + "Users/AdminSearch";