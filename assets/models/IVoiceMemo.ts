import {IActivity} from "./IActivity";
import {IModel} from "./IModel";

export interface IVoiceMemo extends IModel {
	path: string;
	activity: IActivity | null;
}