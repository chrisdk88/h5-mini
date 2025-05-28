import { baseApiUrl } from "../baseApiUrl";

//------------------------ Users ------------------------//

export const putUsersBanURL = baseApiUrl + "Users/banUser/{userId}";

export const putUsersRoleURL = baseApiUrl + "Users/Role/{userId}";

export const putUsersincreaseExpURL = baseApiUrl + "Users/increaseExp/{userId}";

export const putUsersEditURL = baseApiUrl + "Users/Edit/{userId}";