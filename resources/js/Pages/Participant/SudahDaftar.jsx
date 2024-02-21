import React, { useRef } from 'react'
import { Head, useForm } from '@inertiajs/react'

import GuestLayout from '@/Layouts/GuestLayout'
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'
import Button from '@/Components/Button'
import EventSelectionInput from '../Event/SelectionInput'
import FormInput from '@/Components/FormInput'

export default function SudahDaftar(props) {

    const inputRef = useRef()
    const { data, setData, post, processing, errors } = useForm({
        employee_code: null,
        name: null,
        phone: null,
        email: null,
        unit: null,
        agency: null,
    })

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
            <Head title="Terima Kasih" />
            <div>
                <div className="mx-auto sm:px-6 lg:px-1 justify-center">
                    <div className="overflow-hidden p-4 shadow-sm sm:rounded-lg bg-white dark:bg-gray-800 flex flex-col">
                        <div className="text-xl font-bold mb-4 mx-auto text-center">JALAN SANTAI KABUPATEN MAROS </div>
                        <div className="text-4xl font-black mb-4 text-center">Kode Doorprize : { props.employee_code}</div>

                        {/* <div className="m-8 mx-auto">
                            <a className='bg-amber-200 font-medium px-3 py-2 m-8 text-slate-700 rounded-lg hover:bg-slate-100 hover:text-slate-900' target='_blank' href="https://api.whatsapp.com/send?phone=6281355071767&text=Halo%20Terima%20Telah%20Mendaftar%20di%20Acara JALAN%20SANTAI%20KABUPATEN%20MAROS&source=&data=">Klik WhatsApp</a>
                        </div> */}
                        <div className="text-xl mb-4 mx-auto text-center text-rose-500">Screenshoot Kode Doorprize ini, Sebagai bukti </div>
                        <div className="text-xl font-black mb-4 text-center">Peserta : { props.total} Orang</div>
                    <a size="sm" className='bg-sky-500 px-3 py-2 rounded mx-auto justify-center' href='/daftar'>
                                    DAFTAR
                    </a>
                    </div>
                </div>
            </div>
        </GuestLayout>
    )
}
