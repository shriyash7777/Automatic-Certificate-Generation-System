"use client"

import type { CertificateConfig } from "@/app/page"

interface CertificatePreviewProps {
  config: CertificateConfig
}

export function CertificatePreview({ config }: CertificatePreviewProps) {
  const formatDate = (dateString: string) => {
    const date = new Date(dateString)
    return date.toLocaleDateString("en-GB", {
      day: "2-digit",
      month: "short",
      year: "numeric",
    })
  }

  const getBorderClass = () => {
    switch (config.layout.borderStyle) {
      case "geometric":
        return "bg-gradient-to-br from-green-400 via-yellow-400 to-blue-400"
      case "classic":
        return "bg-gradient-to-r from-amber-600 to-amber-800"
      case "modern":
        return "bg-gradient-to-r from-slate-600 to-slate-800"
      default:
        return "bg-gradient-to-br from-green-400 via-yellow-400 to-blue-400"
    }
  }

  const getQRPosition = () => {
    switch (config.layout.qrPosition) {
      case "left":
        return "left-12"
      case "right":
        return "right-12"
      case "center":
        return "left-1/2 transform -translate-x-1/2"
      default:
        return "left-12"
    }
  }

  return (
    <div className="w-full aspect-[4/3] relative overflow-hidden print:aspect-auto print:h-screen">
      <div className="absolute inset-0 p-2">
        {/* Outer geometric border */}
        <div className="relative w-full h-full">
          {/* Corner triangular decorations */}
          <div className="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-green-500 to-yellow-500 transform rotate-45 -translate-x-16 -translate-y-16"></div>
          <div className="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-orange-500 to-yellow-500 transform rotate-45 translate-x-16 -translate-y-16"></div>
          <div className="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-green-500 to-blue-500 transform rotate-45 -translate-x-16 translate-y-16"></div>
          <div className="absolute bottom-0 right-0 w-32 h-32 bg-gradient-to-tl from-blue-500 to-orange-500 transform rotate-45 translate-x-16 translate-y-16"></div>

          {/* Main certificate area */}
          <div className="absolute inset-4 bg-white shadow-lg">
            <div className="absolute inset-3 border-2 border-gray-400">
              <div className="px-8 pt-4">
                <div className="flex items-start justify-between mb-3">
                  {config.layout.showLogos && (
                    <div className="flex flex-col items-center">
                      <div className="w-16 h-16 bg-gradient-to-br from-green-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs mb-1">
                        DSES
                      </div>
                    </div>
                  )}
                  <div className="flex-1 text-center">
                    <h1
                      className={`text-base font-bold ${config.fonts.heading} leading-tight tracking-wide`}
                      style={{ color: config.colors.primary }}
                    >
                      {config.companyName}
                    </h1>
                    <div className="w-32 h-px bg-gray-300 mx-auto my-2"></div>
                    <p
                      className={`text-sm ${config.fonts.body} font-medium`}
                      style={{ color: config.colors.secondary }}
                    >
                      {config.companySubtitle}
                    </p>
                    <p className={`text-xs ${config.fonts.body} mt-1 text-gray-600 tracking-wider`}>
                      {config.companyId}
                    </p>
                  </div>
                  {config.layout.showLogos && (
                    <div className="w-16 h-16 bg-gradient-to-br from-yellow-600 to-orange-600 rounded flex items-center justify-center text-white font-bold text-xs">
                      EMBLEM
                    </div>
                  )}
                </div>

                <div
                  className={`text-xs ${config.fonts.body} text-gray-700 grid grid-cols-4 gap-2 text-center mb-6 border-b border-gray-200 pb-4`}
                >
                  <div className="flex items-center justify-center gap-1">
                    <div className="w-3 h-3 bg-blue-500 rounded-full flex-shrink-0"></div>
                    <span className="truncate">{config.contactInfo.email}</span>
                  </div>
                  <div className="flex items-center justify-center gap-1">
                    <div className="w-3 h-3 bg-green-500 rounded-full flex-shrink-0"></div>
                    <span className="truncate">{config.contactInfo.website}</span>
                  </div>
                  <div className="flex items-center justify-center gap-1">
                    <div className="w-3 h-3 bg-purple-500 rounded-full flex-shrink-0"></div>
                    <span className="truncate">{config.contactInfo.phone}</span>
                  </div>
                  <div className="flex items-center justify-center gap-1">
                    <div className="w-3 h-3 bg-red-500 rounded-full flex-shrink-0"></div>
                    <span className="truncate">{config.contactInfo.address}</span>
                  </div>
                </div>
              </div>

              <div className="text-center mb-6">
                <h2
                  className={`text-4xl font-bold ${config.fonts.heading} tracking-wider`}
                  style={{ color: config.colors.text }}
                >
                  {config.certificateTitle}
                </h2>
              </div>

              <div className={`text-center space-y-4 px-12 ${config.fonts.body}`} style={{ color: config.colors.text }}>
                <p className="text-sm">This is to certify that</p>

                <p
                  className={`text-3xl font-bold italic ${config.fonts.heading} py-2`}
                  style={{ color: config.colors.primary }}
                >
                  {config.studentName}
                </p>

                <p className="text-sm leading-relaxed">
                  has successfully completed <strong className="text-base">{config.courseHours} hours</strong> course of
                </p>

                <p className={`text-2xl font-bold ${config.fonts.heading} py-2`} style={{ color: config.colors.text }}>
                  {config.courseName}
                </p>

                <p className="text-sm">
                  From <strong>{formatDate(config.startDate)}</strong> to <strong>{formatDate(config.endDate)}</strong>
                </p>
              </div>

              <div className={`absolute bottom-12 ${getQRPosition()}`}>
                <div className="text-center">
                  <div className="w-20 h-20 border-2 border-gray-800 bg-white flex items-center justify-center mb-2">
                    <div className="w-16 h-16 bg-black opacity-80 flex items-center justify-center">
                      <div className="grid grid-cols-4 gap-px">
                        {Array.from({ length: 16 }).map((_, i) => (
                          <div key={i} className={`w-1 h-1 ${Math.random() > 0.5 ? "bg-white" : "bg-black"}`}></div>
                        ))}
                      </div>
                    </div>
                  </div>
                  <p className="text-xs text-gray-600 font-medium">Vasudha Dnyanam Eshwaram</p>
                </div>
              </div>

              <div className="absolute bottom-12 right-12 text-center">
                <div className="w-40 border-b-2 border-gray-800 mb-3"></div>
                <p className={`font-bold text-lg ${config.fonts.body}`} style={{ color: config.colors.text }}>
                  {config.instructorName}
                </p>
                <p className={`text-sm ${config.fonts.body} font-semibold`} style={{ color: config.colors.text }}>
                  {config.instructorTitle}
                </p>
              </div>

              <div className="absolute bottom-4 left-12">
                <p className="text-xs text-gray-600 font-medium">
                  Certificate ID: CERT-2025-
                  {Math.floor(Math.random() * 100000)
                    .toString()
                    .padStart(6, "0")}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
