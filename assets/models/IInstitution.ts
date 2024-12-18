import {IContact} from "./IContact";
import {IUser} from "./IUser";
import {IModel} from "./IModel";
import {IActivity} from "./IActivity";

export interface IInstitution extends IModel {
	clientId?: string;
	name: string;
	street: string;
	houseNumber: string;
	postbox: string;
	city: string;
	zipCode: string;
	country: string;
	longitude?: string;
	latitude?: string;
	users: IUser[];
	contacts: IContact[];
	activities: IActivity[];
}
