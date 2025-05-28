import { localApiUrl } from "../../localApiUrl";

//------------------------ Users ------------------------//

export const putUsersBanURL = localApiUrl + "Users/banUser/{userId}";

export const putUsersRoleURL = localApiUrl + "Users/Role/{userId}";

export const putUsersincreaseExpURL = localApiUrl + "Users/increaseExp/{userId}";

export const putUsersEditURL = localApiUrl + "Users/Edit/{userId}";