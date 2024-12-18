import {IActivity} from "./IActivity";
import {IModel} from "./IModel";

export interface ITask extends IModel {
	description: string;
	completed: boolean;
	activity: IActivity | null;
}