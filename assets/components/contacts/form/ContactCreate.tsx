import React from "react";
import {useForm} from "react-hook-form";
import {Box, Button, Container, Paper, Typography} from "@mui/material";
import {ContactForm} from "./ContactForm";
import {IInstitution} from "../../../models/IInstitution";
import {useNavigate, useParams} from "react-router-dom";
import {fetchDataReactQuery, postDataReactQuery} from "../../../utils/HttpRequestUtil";
import {useMutation, useQuery} from "@tanstack/react-query";
import {ErrorComponent} from "../../ErrorComponent";
import {IContact} from "../../../models/IContact";
import {FormTypesEnum} from "../../../enums/ComponentPropsEnums";
import { ErrorTypesEnum } from "../../../enums/ErrorTypesEnum";

export function ContactCreate() {
	const navigate = useNavigate();
	const {external} = useParams();

	const {register, control, handleSubmit, setValue, formState: {errors}} = useForm<IContact>({
		defaultValues: {
			firstName: "",
			lastName: "",
			email1: external ?? "",
			email2: null,
			phoneNumber1: "",
			phoneNumber2: null,
			jobTitle: "",
			institutions: []
		}
	});

	const {data: institutions = [], status} = useQuery<IInstitution[]>(
		['institutions'],
		() => fetchDataReactQuery("/institutions")
	);

	const createContactMutation = useMutation(
		{
			mutationFn: (data: object) => postDataReactQuery('/contacts', data),
			onSuccess: () => navigate('/contacts')
		}
	);

	function onSubmit(data: object) {
		createContactMutation.mutate(data)
	}

	if (status === 'error') return <ErrorComponent type={ErrorTypesEnum.Fetch}/>;

	return (
		<Container component="main" maxWidth="sm" sx={{mb: 4}}>
			<Paper variant="outlined" sx={{my: {xs: 3, md: 6}, p: {xs: 2, md: 3}}}>
				<Typography component="h1" variant="h4" align="center">
					New Contact
				</Typography>
				<form onSubmit={handleSubmit(onSubmit)}>
					<ContactForm register={register} errors={errors} control={control}
								 setValue={setValue} institutions={institutions} type={FormTypesEnum.Create}
								 contact={null}/>
					<Box sx={{display: 'flex', justifyContent: 'flex-end'}}>
						<Button
							type="submit"
							variant="contained"
							sx={{mt: 4}}>
							Save
						</Button>
					</Box>
				</form>
			</Paper>
		</Container>
	);
}