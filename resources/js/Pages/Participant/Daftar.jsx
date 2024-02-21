import React, { useRef } from 'react'
import { Head, useForm } from '@inertiajs/react'

import GuestLayout from '@/Layouts/GuestLayout'
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'
import Button from '@/Components/Button'
import EventSelectionInput from '../Event/SelectionInput'
import FormInput from '@/Components/FormInput'

export default function Daftar(props) {

    const inputRef = useRef()
    const { data, setData, post, processing, errors } = useForm({
        employee_code: null,
        name: null,
        nik: null,
        phone: null,
        // email: null,
        // unit: null,
        // agency: null,
    })

    const handleSubmit = () => {
        post(route('participant.daftar'))
    }

     const handleOnChange = (event) => {
        setData(
            event.target.name,
            event.target.type === 'checkbox'
                ? event.target.checked
                    ? 1
                    : 0
                : event.target.value
        )
    }

    return (
        <GuestLayout>
            <Head title="DAFTAR" />

            <div>
                <div className="mx-auto sm:px-6 lg:px-1">
                    <div className="overflow-hidden p-4 shadow-sm sm:rounded-lg bg-white dark:bg-gray-800 flex flex-col">
                        {/* <div className="text-xl font-bold mb-4 text-center">DAFTAR </div> */}
                        <div className="text-xl font-bold mb-4 text-center">3 TAHUN KEPEMIMPINAN BUPATI & WAKIL BUPATI MAROS</div>
                        <div className="text-xl font-bold mb-4">Total Peserta : { props.total} Orang </div>

                            <FormInput
                                name="name"
                                autoFocus={true}
                                onChange={handleOnChange}
                                label="Nama"
                                error={errors.name}
                            />
                            <FormInput
                                name="nik"
                                onChange={handleOnChange}
                                label="N I K (16 Digit)"
                                type="number"
                                error={errors.nik}
                            />
                            <FormInput
                                name="phone"
                                onChange={handleOnChange}
                                label="No WhatsApp"
                                type="number"
                                error={errors.phone}
                            />
                            {/* <FormInput
                                name="email"
                                onChange={handleOnChange}
                                label="Email"
                                error={errors.email}
                            />
                            <FormInput
                                name="unit"
                                onChange={handleOnChange}
                                label="Unit Kerja"
                                error={errors.unit}
                            />
                            <FormInput
                                name="agency"
                                onChange={handleOnChange}
                                label="Instansi"
                                error={errors.agency}
                            /> */}
                        <div className="mt-2">
                            <Button
                                onClick={handleSubmit}
                                processing={processing}
                            >
                                Simpan
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    )
}
