import * as React from 'react';
import {useEffect, useState} from 'react';
import {useAudioRecorder} from 'react-audio-voice-recorder';
import StopCircleIcon from '@mui/icons-material/StopCircle';
import {Box, IconButton, Stack, Tooltip, Typography} from "@mui/material";
import MicIcon from '@mui/icons-material/Mic';
import {deleteDataReactQuery, postDataReactQuery} from "../../../utils/HttpRequestUtil";
import {IActivity} from "../../../models/IActivity";
import ClearIcon from '@mui/icons-material/Clear';
import {useMutation, useQueryClient} from "@tanstack/react-query";

interface Props {
	activity: IActivity;
}

export function ActivityVoiceMemo({activity}: Props) {
	const recorderControls = useAudioRecorder();
	const [isDisabled, setIsDisabled] = useState(!!activity.voiceMemo);
	// recorderControls.recordingBlob is always undefined at first
	const [recording, setRecording] = useState<Blob | undefined>(recorderControls.recordingBlob);
	const queryClient = useQueryClient();

	useEffect(() => {
		if (recorderControls.recordingBlob != undefined) {
			setRecording(recorderControls.recordingBlob);
			saveRecording(recorderControls.recordingBlob);
		}
	}, [recorderControls.recordingBlob]);

	const handleRecordingStart = () => recorderControls.startRecording();

	const handleRecordingStop = () => {
		recorderControls.stopRecording();
		setIsDisabled(true);
	}

	const handleRecordingReset = () => {
		setIsDisabled(false);
		setRecording(undefined);
		deleteRecording();
	}

	const modifyVoiceMemoMutation = useMutation(
		{
			mutationFn: (formData: object) => postDataReactQuery(`/file/voice-memo/${activity.id}`, formData),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const saveRecording = (recordingBlob: any) => {
		const formData = new FormData();
		formData.append('voice_memo', recordingBlob);

		modifyVoiceMemoMutation.mutate(formData);
	}

	const deleteVoiceMemoMutation = useMutation(
		{
			mutationFn: () => deleteDataReactQuery(`/file/voice-memo/${activity.id}`),
			onSuccess: () => {
				queryClient.invalidateQueries({queryKey: ['activity']});
			}
		}
	);

	const deleteRecording = () => deleteVoiceMemoMutation.mutate();

	return (
		<>
			<Box mb={1} color="text.secondary">
				<Typography component="span" variant="body1">
					Voice memo
				</Typography>
			</Box>
			<Box mb={1}>
				{recorderControls.isRecording ? (
					<>
						<Tooltip title="Stop recording">
							<IconButton color="primary" onClick={handleRecordingStop}>
								<StopCircleIcon/>
							</IconButton>
						</Tooltip>
						{isDisabled ? "" : <span className="blink">&nbsp;Recording...</span>}
					</>
				) : (
					<Tooltip title="Start recording">
						<IconButton color="primary" disabled={isDisabled} onClick={handleRecordingStart}>
							<MicIcon/>
						</IconButton>
					</Tooltip>
				)}
			</Box>
			<Box>
				{(recording || activity.voiceMemo?.path) && (
					<Stack direction="row" spacing={2}>
						<audio controls>
							<source src={recording
								? URL.createObjectURL(recording)
								: activity.voiceMemo?.path.split("public")[1]}
									type="audio/mp3"/>
						</audio>
						<Tooltip title="Delete memo">
							<IconButton color="default" onClick={handleRecordingReset}>
								<ClearIcon/>
							</IconButton>
						</Tooltip>
					</Stack>
				)}
			</Box>
		</>
	);
}