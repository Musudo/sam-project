import React, {useEffect, useState} from 'react';
import {Box, Button, Container, Paper, Typography} from '@mui/material';
import {useForm} from 'react-hook-form';
import {ContactForm} from "./ContactForm";
import {IContact} from '../../../models/IContact';
import {useParams} from "react-router-dom";
import {fetchDataReactQuery, patchDataReactQuery} from '../../../utils/HttpRequestUtil';
import {IInstitution} from "../../../models/IInstitution";
import {FormSubmitSnackbar} from '../../FormSubmitSnackbar';
import {useMutation, useQuery} from '@tanstack/react-query';
import {ErrorComponent} from '../../ErrorComponent';
import {FormTypesEnum} from "../../../enums/ComponentPropsEnums";
import { ErrorTypesEnum } from '../../../enums/ErrorTypesEnum';

export function ContactEdit() {
	const [openSnackbar, setOpenSnackbar] = useState(false);
	const {guid} = useParams();

	const {register, handleSubmit, control, setValue, reset, formState: {errors}} = useForm<IContact>({
		defaultValues: {
			firstName: "",
			lastName: "",
			email1: "",
			email2: null,
			phoneNumber1: "",
			phoneNumber2: null,
			jobTitle: "",
			institutions: []
		}
	});

	const {data: contact = null, status: contactStatus} = useQuery<IContact>(
		['contacts', guid],
		() => fetchDataReactQuery(`/contacts/${guid}`)
	);

	const {data: institutions = [], status: institutionsStatus} = useQuery<IInstitution[]>(
		['institutions'],
		() => fetchDataReactQuery('/institutions')
	);

	useEffect(() => {
		if (contactStatus === 'success' && contact) reset(contact);
	}, [contact, contactStatus]);

	const modifyContactMutation = useMutation(
		{
			mutationFn: (data: object) => patchDataReactQuery(`/contacts/${contact?.id}`, data),
			onSuccess: () => setOpenSnackbar(true)
		}
	);

	function onSubmit(data: any) {
		modifyContactMutation.mutate(data);
	}

	if (contactStatus === 'error' || institutionsStatus === 'error') return <ErrorComponent type={ErrorTypesEnum.Fetch}/>;

	return (
		<Container component='main' maxWidth='sm' sx={{mb: 4}}>
			<FormSubmitSnackbar setOpen={setOpenSnackbar} open={openSnackbar} message='Contact updated'/>
			<form onSubmit={handleSubmit(onSubmit)}>
				<Paper variant='outlined' sx={{my: {xs: 3, md: 6}, p: {xs: 2, md: 3}}}>
					<Typography component='h1' variant='h4' align='center'>
						Edit Contact
					</Typography>
					<ContactForm register={register} errors={errors} control={control}
								 setValue={setValue} institutions={institutions} type={FormTypesEnum.Edit}
								 contact={contact}/>
					<Box sx={{display: 'flex', justifyContent: 'flex-end'}}>
						<Button
							type='submit'
							variant="contained"
							sx={{mt: 3, ml: 1}}>
							Save
						</Button>
					</Box>
				</Paper>
			</form>
		</Container>
	);
}