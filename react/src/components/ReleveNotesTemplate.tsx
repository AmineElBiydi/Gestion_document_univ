import React, { useRef } from 'react';
import { useReactToPrint } from 'react-to-print';
import { Button } from "@/components/ui/button";
import { X, Printer } from "lucide-react";
import { format } from "date-fns";
import { fr } from "date-fns/locale";

interface ReleveNotesProps {
    data: any;
    onClose: () => void;
}

export default function ReleveNotesTemplate({ data, onClose }: ReleveNotesProps) {
    const componentRef = useRef(null);

    const handlePrint = useReactToPrint({
        contentRef: componentRef,
        documentTitle: `Releve de notes - ${data.student.nom} ${data.student.prenom}`,
    });

    const { student, details } = data;
    const currentDate = format(new Date(), "d MMMM yyyy", { locale: fr });

    // Format birth date in French
    const birthDate = student.date_naissance
        ? format(new Date(student.date_naissance), "d MMMM yyyy", { locale: fr })
        : "Non renseigné";

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 overflow-auto">
            <div className="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
                {/* Header Actions */}
                <div className="flex justify-between items-center p-4 border-b">
                    <h2 className="text-lg font-semibold">Aperçu du document</h2>
                    <div className="flex gap-2">
                        <Button onClick={() => handlePrint()} variant="default" className="gap-2">
                            <Printer className="h-4 w-4" />
                            Imprimer / PDF
                        </Button>
                        <Button onClick={onClose} variant="ghost" size="icon">
                            <X className="h-4 w-4" />
                        </Button>
                    </div>
                </div>

                {/* Scrollable Content */}
                <div className="overflow-auto p-8 bg-gray-100 flex justify-center">
                    <div
                        ref={componentRef}
                        className="bg-white p-8 shadow-sm w-[210mm] min-h-[297mm]"
                        style={{ fontFamily: 'Arial, sans-serif' }}
                    >
                        {/* Header */}
                        <div className="border-2 border-black p-4 mb-5">
                            <div className="flex justify-between mb-2 text-sm font-bold">
                                <span>Universite Abdelmalek Essaadi</span>
                                <span>جامعة عبد المالك السعدي</span>
                            </div>
                            <div className="flex justify-between text-sm">
                                <span>Annee universitaire {details.annee_universitaire}</span>
                                <span>السنة الجامعية</span>
                            </div>
                        </div>

                        <div className="mb-4 text-xs font-bold flex justify-between">
                            <span>Ecole Nationale des Sciences Appliquees Tetouan</span>
                            <span>المدرسة الوطنية للعلوم التطبيقية بتطوان</span>
                        </div>

                        {/* Title */}
                        <div className="text-center font-bold text-lg border-2 border-black p-2 mb-1 uppercase bg-gray-50">
                            RELEVE DE NOTES ET RESULTATS
                        </div>
                        <div className="text-right text-xs mb-3">Page : 1 / 1</div>

                        {/* Session */}
                        <div className="text-center border border-black p-2 font-bold mb-5 uppercase bg-gray-50">
                            Session {details.session === 'normale' ? '1' : '2'}
                        </div>

                        {/* Student Info */}
                        <div className="mb-6 text-xs border p-4">
                            <div className="font-bold mb-3 text-sm uppercase">{student.nom} {student.prenom}</div>
                            <div className="grid grid-cols-2 gap-y-2">
                                <div>
                                    <span className="font-bold">N° Etudiant:</span> {student.apogee}
                                </div>
                                <div>
                                    <span className="font-bold">CNE / CIM:</span> {student.cin}
                                </div>
                                <div>
                                    <span className="font-bold">Né le :</span> {birthDate}
                                </div>
                                <div>
                                    <span className="font-bold">à :</span> {student.lieu_naissance || "Non renseigné"}
                                </div>
                                <div className="col-span-2 mt-2 pt-2 border-t">
                                    <span className="font-bold">Inscrit en : </span>
                                    <span className="font-bold uppercase">{details.niveau} - {details.filiere}</span>
                                </div>
                            </div>
                            <div className="mt-3">a obtenu les notes suivantes :</div>
                        </div>

                        {/* Table */}
                        <table className="w-full border-collapse text-xs mb-6">
                            <thead>
                                <tr className="bg-gray-200">
                                    <th className="border border-black p-2 text-left" style={{ width: '50%' }}>Module</th>
                                    <th className="border border-black p-2 text-center" style={{ width: '20%' }}>Note / 20</th>
                                    <th className="border border-black p-2 text-center" style={{ width: '15%' }}>Résultat</th>
                                    <th className="border border-black p-2 text-center" style={{ width: '15%' }}>Session</th>
                                </tr>
                            </thead>
                            <tbody>
                                {details.resultats && Array.isArray(details.resultats) ? (
                                    details.resultats.map((item: any, index: number) => (
                                        <tr key={index}>
                                            <td className="border border-gray-400 p-2">{item.module}</td>
                                            <td className="border border-gray-400 p-2 text-center font-medium">{Number(item.note).toFixed(2)} / 20</td>
                                            <td className="border border-gray-400 p-2 text-center">
                                                {item.resultat === 'Validé' ? 'Validé' : 'Non Validé'}
                                            </td>
                                            <td className="border border-gray-400 p-2 text-center">
                                                S{details.niveau?.replace('S', '')} {details.annee_universitaire?.split('-')[0]}/{details.annee_universitaire?.split('-')[1]?.substring(2)}
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={4} className="border border-gray-400 p-4 text-center text-muted-foreground">
                                            Aucune note disponible
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>

                        {/* Result */}
                        <div className="mt-5 border-2 border-black p-4 bg-gray-50 flex justify-between items-center font-bold text-xs uppercase">
                            <span>Résultat d'admission :</span>
                            <span>Moyenne: {Number(details.moyenne).toFixed(3)} / 20</span>
                            <span>{details.decision}</span>
                            <span>Mention: {details.mention || "Passable"}</span>
                            {/* Ranking removed as not in DB */}
                        </div>

                        {/* Signature Section */}
                        <div className="mt-12 flex justify-between items-end">
                            <div className="text-xs">
                                <p className="font-bold">Moyenne &gt;= 10 ={'>'} Validé</p>
                                <p>Note &lt; 5 ={'>'} Note éliminatoire</p>
                            </div>

                            <div className="text-center">
                                {/* Signatures svg */}
                                <div className="mb-2">
                                    <svg width="150" height="100" viewBox="0 0 150 100" xmlns="http://www.w3.org/2000/svg">
                                        {/* Stamp Circle */}
                                        <circle cx="75" cy="50" r="35" fill="none" stroke="#1e40af" strokeWidth="2" opacity="0.7" />
                                        <circle cx="75" cy="50" r="25" fill="none" stroke="#1e40af" strokeWidth="1" opacity="0.5" />

                                        {/* Text around stamp */}
                                        <path id="curve" d="M 50,50 A 25,25 0 0,1 100,50" fill="none" />
                                        <text fontSize="6" fill="#1e40af" textAnchor="middle">
                                            <textPath href="#curve" startOffset="50%">
                                                Université A. Essaâdi
                                            </textPath>
                                        </text>

                                        {/* Center text */}
                                        <text x="75" y="45" fontSize="5" textAnchor="middle" fill="#1e40af" fontWeight="bold">ENSA</text>
                                        <text x="75" y="52" fontSize="5" textAnchor="middle" fill="#1e40af" fontWeight="bold">TETOUAN</text>
                                        <text x="75" y="60" fontSize="5" textAnchor="middle" fill="#1e40af">Service Scolarité</text>

                                        {/* Signature Doodle */}
                                        <path d="M 60 70 Q 80 50 100 70 T 130 65" stroke="#1e3a8a" strokeWidth="2" fill="none" />
                                    </svg>
                                </div>

                                <div className="text-xs">
                                    <p className="mb-1">Fait à Tétouan, le {currentDate}</p>
                                    <p className="font-bold">Le Directeur</p>
                                </div>
                            </div>
                        </div>

                        {/* Footer Text */}
                        <div className="mt-8 text-xs text-center border-t pt-2 text-gray-500">
                            <p className="italic">
                                Avis important : Il ne peut être délivré qu'un seul exemplaire du présent relevé de notes. Aucun duplicata ne sera fourni.
                            </p>
                            <p>ENSA Tétouan - Avenue de la Palestine Mhanech I, Tétouan - Maroc</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
