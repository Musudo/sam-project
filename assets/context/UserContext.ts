import {createContext} from "react";
import {IUser} from "../models/IUser";

// use of user context to easily change to another provider
export interface IUserContext {
	user: IUser | null;
}

const UserContext = createContext<IUserContext>({
	user: null,
});

export default UserContext;
