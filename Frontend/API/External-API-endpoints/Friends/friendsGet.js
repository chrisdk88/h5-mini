import { externalApiUrl } from "../../externalApiUrl";

//------------------------ Friends ------------------------//
export const getFriendsURL =  externalApiUrl + "Friends";

export const getUserFriendsURL =  externalApiUrl + "Friends/getUsersFriends/{userId}";

export const getFriendsRequestsURL =  externalApiUrl + "Friends/requests/{userId}";

export const getFriendsSearchURL =  externalApiUrl + "Friends/Search";