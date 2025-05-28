import { localApiUrl } from "../../localApiUrl";

//------------------------ Friends ------------------------//
export const getFriendsURL =  localApiUrl + "Friends";

export const getUserFriendsURL =  localApiUrl + "Friends/getUsersFriends/{userId}";

export const getFriendsRequestsURL =  localApiUrl + "Friends/requests/{userId}";

export const getFriendsSearchURL =  localApiUrl + "Friends/Search";