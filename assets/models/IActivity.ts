import {IReview} from "./IReview";
import {IContact} from "./IContact";
import {IUser} from "./IUser";
import {ITask} from "./ITask";
import {IExternalParticipant} from "./IExternalParticipant";
import {IInstitution} from "./IInstitution";
import {IVoiceMemo} from "./IVoiceMemo";
import {IModel} from "./IModel";
import {ITag} from "./ITag";
import {Dayjs} from "dayjs";

export interface IActivity extends IModel {
	subject: string;
	tags: ITag[];
	externalNote?: string;
	internalNote?: string;
	type: string;
	start: string;
	end: string;
	voiceMemo?: IVoiceMemo | null;
	review?: IReview | null;
	user: number;
	contacts: IContact[];
	tasks?: ITask[];
	externalParticipants?: IExternalParticipant[];
	institution: IInstitution | null;
	emailSentAt: Date | null;
}
