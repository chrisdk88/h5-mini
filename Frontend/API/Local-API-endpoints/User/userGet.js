import { localApiUrl } from "../../localApiUrl";

//------------------------ Users ------------------------//
export const getUsersURL = localApiUrl + "Users";

export const getUsersIdURL = localApiUrl + "Users/{userId}";

export const getUsersExpLvlURL = localApiUrl + "Users/GetUsersExpAndLevel/{userId}";

export const getUsersAdminURL = localApiUrl + "Users/AdminSearch";