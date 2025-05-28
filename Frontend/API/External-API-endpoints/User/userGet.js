import { externalApiUrl } from "../../externalApiUrl";

//------------------------ Users ------------------------//
export const getUsersURL = externalApiUrl + "Users";

export const getUsersIdURL = externalApiUrl + "Users/{userId}";

export const getUsersExpLvlURL = externalApiUrl + "Users/GetUsersExpAndLevel/{userId}";

export const getUsersAdminURL = externalApiUrl + "Users/AdminSearch";