import {IInstitution} from "./IInstitution";
import {IActivity} from "./IActivity";
import {IModel} from "./IModel";

export interface IContact extends IModel {
	firstName: string;
	lastName: string;
	email1: string;
	email2?: string | null
	phoneNumber1: string;
	phoneNumber2?: string | null;
	jobTitle: string;
	institutions: IInstitution[];
	activities?: IActivity[];
}
