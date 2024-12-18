import {IModel} from "./IModel";
import {IAttachment} from "./IAttachment";

export interface IReview extends IModel {
	title: string;
	content: string;
	activity: number;
	user: number;
	attachments: IAttachment[] | [];
}
