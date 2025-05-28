import { baseApiUrl } from "../baseApiUrl";

//------------------------ Users ------------------------//
export const getUsersURL = baseApiUrl + "Users";

export const getUsersIdURL = baseApiUrl + "Users/{userId}";

export const getUsersExpLvlURL = baseApiUrl + "Users/GetUsersExpAndLevel/{userId}";

export const getUsersAdminURL = baseApiUrl + "Users/AdminSearch";