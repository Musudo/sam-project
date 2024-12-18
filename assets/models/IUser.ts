import {IModel} from "./IModel";
import {IActivity} from "./IActivity";
import {IInstitution} from "./IInstitution";

export interface IUser extends IModel {
	email: string;
	firstName: string;
	lastName: string;
	roles: string[];
	activities: IActivity[];
	institutions: IInstitution[];
}
